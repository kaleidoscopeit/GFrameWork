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
    $path        = $_->CALL_URN;
    $response[0] = $this->call($path, $response[1], $flags, $_);
    return         json_encode($response,JSON_PARTIAL_OUTPUT_ON_ERROR);
  }



  /* Structured RPC API.
  *
  *
  * $rpc_name    : RPC URN in dot notation e.g. "auth.check"
  *
  * $buffer      : Multidimensional array used ad Exchange buffer.
                   Contains the input values from the caller and the output
                   result after th execution. The effective content depends
  *                on the $output switches (default : only the result)
  *
  * $options     : behavioral witches
  *
  *   - stack  ->  the result of current macro will be merged with the input
  *                values. Every previous similar values will be overwritten
  *   - path   ->  the result of current call will be put in a sub array of the
  *                buffer, labeled as the URN of the call
  *   - label  ->  the result of current call will be put in a sub-array of the
  *                buffer with a key named as the next value in the $options array
  *   - die    ->  on error (function report 'false') print a message and die
  *
  *
  * By convention, the function returns an integer value; the programmer has
  * the freedom to choose right exit codes.
  */

  function call($rpc_name, &$buffer, $options, $_)
  {
    if (!isset($buffer)) $buffer = array();

    $context_path['system'] = $_->CORE_PATH . '/rpcs/';
    $context_path['user']   = 'rpcs/';

    /* TODO : checks for valid characters */    
    $options  = explode(',', $options || array());
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
    $rpc_check = $this->_call_param_check($buffer, $this->rpcs[$rpc_name]);

    if($rpc_check !== true) {
      $buffer['STDERR'] = $rpc_check;
      return false;
    }

    /* call the RPC */
    $rpc_status = $function($_, $buffer, $rpc_response);

    /* ON FAILURE BEHAVIOUR */

    /* in case of FAILURE do following dependig by choosen output option */
    if ($rpc_status == false) {

      /* directly die */
      if (in_array('die',$options))
      die("RPC '" . $rpc_name . "' failed.");

      /* try to use default error dialog */
      if (in_array('dialog',$options) && $_->ROOT)
      $_->ROOT->system_error(print_r($rpc_response['STDERR']));

      /* else prints the error to the stdout (default behaviour)*/
      //else print_r($rpc_response['STDERR']);
    }


    /* OUTPUT OPTIONS */

    /* put results in a custom labeled array */
    if (in_array('label', $options)) {
      $label = array_search('label', $options);
      $output_buffer[$options[$label+1]] = $rpc_response;
    }


    /* put results in a labeled array with the class name as label */
    if (in_array('path',$options))
    $output_buffer[$rpc_name] = $rpc_response;

    if (!isset($output_buffer)) $output_buffer = $rpc_response;

    /* merge input buffer with the call response */
    if (in_array('stack',$options)) {
      if (!is_array($output_buffer)) $output_buffer = array($output_buffer);
      $buffer = array_merge($buffer, $output_buffer);
    }

    /* or returns only the call response */
    else $buffer = $output_buffer;

    /* CALL END */
    return $rpc_status;
  }

  function _call_param_check(&$buffer, $call)
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
            eval('if(@isset(' . $rule[1] . ')) $buffer[$name] = ' . $rule[1] . ';');
            //echo $buffer[$name];
            break;

          case 'call' :
            //echo $rule[1];
            $rule[1] = explode(';', $rule[1]);
            $call = $rule[1][0];
            if(isset($rule[1][1])) {
              $args = explode(',', $rule[1][1]);
              foreach($args as $argv){
                $argv = explode('=', $argv);
                $buffer[$name][$argv[0]] = $argv[1];
              }
            }
            $this->call($call, $buffer[$name], NULL, $_);
            break;

          /* composite string. May be made by a mix of quoted text and variables */
          case 'string' :
            $pre = ""; $post = "";
            eval('$buffer[$name] = ' . $rule[1] . ';');
            if(substr($buffer[$name],0,1) != '"') $pre = '"';
            if(substr($buffer[$name],-1,1) != '"') $post = '"';
            $buffer[$name] = $pre . $buffer[$name] . $post;
            eval('$buffer[$name] = ' . $buffer[$name] . ';');
            break;

          case 'integer' :
            $buffer[$name] = (integer)eval("return $rule[1];");
            break;

          case 'boolean' :
            if($rule[1] != "0" && $rule[1] != "1" &&
               strtoupper($rule[1]) != "TRUE" &&
               strtoupper($rule[1]) != "FALSE") {
              $bad = "Required origin type not match ("
                   . "param : '" . $name . "', "
                   . "required : boolean (0,1,TRUE,FALSE), "
                   . "found : '"  . $rule[1] . "').";
            }

            $buffer[$name] = (bool)$rule[1];
            break;

          case 'code' :
            eval('$buffer[$name] = ' . $rule[1] . ";");
            break;

          case 'date' :
            eval ('$date = ' . $rule[1] . ';');
            $buffer[$name] = (1 === preg_match('~[0-9]~', $date) ?
              strtotime($date) : false);
            break;
        }

        /* breaks if one of the origin gives a result */
        if(isset($buffer[$name])) break;
      }

      /* check strict rules (IT'S A VERY VERY UGLY CODE)*/
      if (!isset($buffer[$name])) {
        if ($options['required'] == true)
          $bad = "Cannot get required param -> '" . $name . "'.";
      }

      else {
        $bad_text = "Required param type not match (".
        "param : '" . $name . "', " .
        "required : '" . $options['type'] . "', " .
        "found : '" . gettype($buffer[$name]) . "').";

        if (gettype($buffer[$name]) != $options['type'])

        /* not alwais a number is passed as it is, but may be passed as string
        * this code verify, in case of a numeric value is required but a
        * string is recognized, if it's really a numberic value */
        if(gettype($buffer[$name]) == 'string' &&
        ($options['type'] == 'float' ||
        $options['type'] == 'bool' ||
        $options['type'] == 'integer')) {

          /* needs a cast to reconize the correct type */
          switch($options['type']) {
            case 'float' :
            case 'bool' :
            case 'integer' :
            if(!is_numeric($buffer[$name])) $bad = $bad_text;
            break;
          }
        }

        /* finally gives an error in no rules matches */
        else $bad = $bad_text;
      }

      if (isset($bad)) {
        return $_error = array(
        'call'   => array($call['name']),
        'signal' => 'PARAM_CHECK_ERROR',
        'info'   => $bad,
        'rule'   => $call[0],
        'param'  => $buffer);
      }
    }

    return true;
  }
}

/* shortcut to RPC function */
function _call($a, &$b, $c = ""){
  global $_;
  return $_->rpc_engine->call($a, $b, $c, $_);
}


/* shortcut to RPC function */
function _gf_rpc($a, &$b, $c = NULL){
  global $_;
  return $_->rpc_engine->call($a, $b, $c, $_);
}
