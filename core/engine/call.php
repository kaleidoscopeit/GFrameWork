<?php

/* This class contains the RPC Engine - let to standardize the use of PHP
 * functions both from server and from clients
 *
 */

class _engine_call
{
  function build($_)
  {
    $response[1] = json_decode($_POST['b'],true);
    $flags       = isset($_POST['h']) ? $_POST['h'] : '';
    $path        = $_->CALL_URI;
    $response[0] = $this->call($path, $response[1], $flags, $_);
    return         json_encode($response,JSON_PARTIAL_OUTPUT_ON_ERROR);
  }



  /* Structured RPC API.
  *
  *
  * $rpc_name    : 'path' of the RPC
  *
  * $_STDIN      :  array to be used as buffer, contains the input values
  *                 when the macro is called and the final content depends
  *                 on $output switches (default : only the result)
  *
  * $options     : list of switches
  *
  *   - stack  ->  the result of current macro will added to the $_STDIN;
  *                every previous similar values will be overwritten
  *   - path   ->  the result of current call will added to the $_STDIN
  *                in a sub array labeled as the path of the call
  *   - label  ->  the result of current call will nested in a sub-array
  *                with a key named as the next value in the $options array
  *   - die    ->  on error (function report 'false') print a message and die
  *
  *
  * returns an integer value, the programmer has the freedom to choose
  * right response.
  */

  function call($rpc_name, &$_STDIN, $options = '', $_)
  {
    if (!isset($_STDIN)) $_STDIN = array();

    $context_path['system'] = '../core/rpc_calls/';
    $context_path['user']   = 'lib/rpc_calls/';

    /* TODO : checks for valid characters */
    $options  = explode(',', $options);
    $rpc_path = $rpc_name . '.php';

    /* imports RPC code (user's rpcs override system ones)*/
    if (is_file($context_path['user'] . $rpc_path))
      require $context_path['user'] . $rpc_path;

    else if (is_file($context_path['system'] . $rpc_path))
      require $context_path['system'] . $rpc_path;

    else
      die("RPC not found : '" . $rpc_name . "'");

    $this->rpcs[$rpc_name]         = $rpc;
    $this->rpcs[$rpc_name]['name'] = $rpc_name;

    $self      = $this->rpcs[$rpc_name];
    $function  = $this->rpcs[$rpc_name][1];

    /* checks inputs parameters rules */
    $rpc_check = $this->_call_param_check($_STDIN, $this->rpcs[$rpc_name]);

    if($rpc_check !== true) {
      $_STDIN['STDERR'] = $rpc_check;
      return false;
    }

    /* call the RPC */
    $rpc_status = $function($_, $_STDIN, $rpc_response);

    /* ON FAILURE BEHAVIOUR */

    /* in case of FAILURE do following dependig by choosen output option */
    if ($rpc_status == false) {

      /* directly die */
      if (in_array('die',$options))
      die("RPC '".$rpc_name."' failed.");

      /* try to use default error dialog */
      if (in_array('dialog',$options) && $_->ROOT)
      $_->ROOT->system_error(print_r($rpc_response['STDERR']));

      /* else prints the error to the stdout (default behaviour)*/
      //else print_r($rpc_response['STDERR']);
    }


    /* OUTPUT OPTIONS */

    /* put results in a custom labeled array */
    if (in_array('label',$options)) {
      $label = array_search('label',$options);
      $output_buffer[$options[$label+1]] = $rpc_response;
    }


    /* put results in a labeled array with the class name as label */
    if (in_array('path',$options))
    $output_buffer[$rpc_name] = $rpc_response;

    if (!isset($output_buffer)) $output_buffer = $rpc_response;

    /* merge input buffer with the call response */
    if (in_array('stack',$options)) {
      if (!is_array($output_buffer)) $output_buffer = array($output_buffer);
      $_STDIN = array_merge($_STDIN, $output_buffer);
    }

    /* or returns only the call response */
    else $_STDIN = $output_buffer;

    /* CALL END */
    return $rpc_status;
  }

  function _call_param_check(&$_STDIN, $call)
  {
    global $_;

    foreach ($call[0] as $name => $options) {

      /* try to fetch the source value depending by 'origin' rules */
      foreach ($options['origin'] as $rule) {
        $rule = explode(':', $rule);

        switch ($rule[0]) {
          /* Single variable assignement */
          case 'variable' :
            //echo $rule[1];eval('echo '.$rule[1]);echo "\n";
            eval('if(@isset('.$rule[1].'))$_STDIN[$name] = '.$rule[1].';');
            //echo $_STDIN[$name];
            break;

          case 'call' :
            //echo $rule[1];
            $rule[1] = explode(';', $rule[1]);
            $call = $rule[1][0];
            if(isset($rule[1][1])) {
              $args = explode(',', $rule[1][1]);
              foreach($args as $argv){
                $argv = explode('=', $argv);
                $_STDIN[$name][$argv[0]] = $argv[1];
              }
            }
            $this->call($call, $_STDIN[$name], NULL, $_);
            break;

          /* composite string. May be made by a mix of quoted text and variables */
          case 'string' :
            eval('$_STDIN[$name] = '.$rule[1].';');
            if(substr($_STDIN[$name],0,1) != '"') $pre = '"';
            if(substr($_STDIN[$name],-1,1) != '"') $post = '"';
            $_STDIN[$name] = $pre.$_STDIN[$name].$post;
            eval('$_STDIN[$name] = '.$_STDIN[$name].';');
            break;

          case 'integer' :           
            $_STDIN[$name] = (integer)eval("return $rule[1];");
            break;

          case 'boolean' :
            $_STDIN[$name] = (bool)eval("return $rule[1];");
            break;

          case 'code' :
            ob_start();
            eval($rule[1].";");
            $_STDIN[$name] = ob_get_contents();
            ob_end_clean();
            break;

          case 'date' :
            eval ('$date = ' . $rule[1] . ';'); 
            $_STDIN[$name] = (1 === preg_match('~[0-9]~', $date) ?
              strtotime($date) : false);
            break;
        }

        /* breaks if one of the origin gives a result */
        if(isset($_STDIN[$name])) break;
      }

      /* check strict rules (IT'S A VERY VERY UGLY CODE)*/
      if (!isset($_STDIN[$name])) {
        if ($options['required'] == true)
        $bad = "Cannot get required param -> '".$name."'.";
      }

      else {
        $bad_text = "Required param type not match (".
        "param : '" . $name . "', " .
        "required : '" . $options['type'] . "', " .
        "found : '" . gettype($_STDIN[$name]) . "').";

        if (gettype($_STDIN[$name]) != $options['type'])

        /* not alwais a number is passed as it is, but may be passed as string
        * this code verify, in case of a numeric value is required but a
        * string is recognized, if it's really a numberic value */
        if(gettype($_STDIN[$name]) == 'string' &&
        ($options['type'] == 'float' ||
        $options['type'] == 'bool' ||
        $options['type'] == 'integer')) {

          /* needs a cast to reconize the correct type */
          switch($options['type']) {
            case 'float' :
            case 'bool' :
            case 'integer' :
            if(!is_numeric($_STDIN[$name])) $bad = $bad_text;
            break;
          }
        }

        /* finally gives an error in no rules matches */
        else $bad = $bad_text;
      }

      if (isset($bad)) {
        return $_error = array(
        'desc'   => $bad,
        'signal' => 'PARAM_CHECK_ERROR',
        'call'   => array($call['name']),
        'rule'   => $call[0],
        'param'  => $_STDIN);
      }
    }

    return true;
  }
}

/* shortcut to RPC function */
function _call($a, &$b, $c = NULL){
  global $_;
  return $_->rpc_engine->call($a, $b, $c, $_);
}


/* shortcut to RPC function */
function _gf_rpc($a, &$b, $c = NULL){
  global $_;
  return $_->rpc_engine->call($a, $b, $c, $_);
}
