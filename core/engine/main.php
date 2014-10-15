<?php
class _
{
   
  function __construct($source)
  {
    /* start session */
    session_start();  

    /* imports the project configuration file and 
       the configuration database engine */
    require "etc/config.php";

    /* imports GIDE static data from browser session */
    $this->static = &$_SESSION[$this->settings['app-uuid']];

    /* global names */
    $this->set_global_names($source);
    
    /* debug calls */
    $message=array('message'=>'call debug');
    $this->call('system.utils.write_debug',$message);
  }                        

  function set_global_names($source){
    /* extract the type of object called ( must be the first part ) */
    $source = explode('/', rtrim($source,"/"));
    
    /* global names */
    $this->CALL_OBJECT = array_shift($source);
    $this->CALL_PATH   = implode('/', $source);
    $this->CALL_URI    = implode('.', $source);
    $this->CALL_TARGET = $source[count($source)-1];
    $this->APP_PATH = '//' . $_SERVER['HTTP_HOST']
                    . dirname($_SERVER['PHP_SELF']);
    $this->CORE_PATH    = '../core';
    $this->WEBGETS_PATH = $this->CORE_PATH . '/webgets/';
  }  

  /* apply the security policy if required and act as hub for various 
     sub funcitions depending by the called object */
  function main()
  {
    /* private loopback to the default name of the gide class */
    $_=$this;


    /* Authentication checkpoint */
//    if (!$_->call('system.auth.check',$_buf))
//      $this->CALL_SOURCE = $this->settings['auth_login_page'];
      
    /* preprocess XML views/reports */
    if($this->CALL_OBJECT == 'views' | 'reports'){
     // require_once '../core/engine/xmlbuilder.php';
      
    }


    switch ($this->CALL_OBJECT) { 
      case 'views' :
      case 'view' :
      case 'subview' : 

        require_once __DIR__ . '/views.php';

        if (!$_->call('system.auth.check',$_buf))
          $this->set_global_names($this->settings['auth_login_page']);

        _engine_views::init();
        
        return _engine_views::build();
        break;

      case 'reports' :
        if (!$_->call('system.auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/reports.php';
        return _engine_reports::build();
        break;
        
      case 'call' :
        $response[1] = unserialize($_POST['b']);
        $flags       = isset($_POST['h']) ? $_POST['h'] : '';
        $path        = $this->CALL_URI;
        $response[0] = $this->call($path, $response[1], $flags);
        echo           json_encode($response,JSON_PARTIAL_OUTPUT_ON_ERROR);

        //echo JSON_ERROR_UNSUPPORTED_TYPE;
        break;
        
      case 'lib' :
        require __DIR__ . '/djl.php';
        return  _engine_djl::get();
        break;

      case 'css' :
        //if (!$_->call('system.auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/css.php';
        _engine_css::init();
        return _engine_css::build();
        break;

      case 'js' :
        //if (!$_->call('system.auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/js.php';
        _engine_js::init();
        return _engine_js::build();
        break;

      case 'upload' :
        require_once __DIR__ . '/upload.php';
        _engine_upload::init();
        return _engine_upload::build();        
        break;

      case 'compile' :
        if (!$_->call('system.auth.check',$_buf)) die('Access Denied');
        require_once __DIR__ . '/compile.php';
        _engine_compile::init();
        return _engine_compile::build($this->CALL_SOURCE);
        break;
        
				default :
					die ('UNKNOWN_OBJECT');
    }
    
    /* dump G-FRAMEWORK static data to browser session */                        
    $_SESSION[$this->settings['app-uuid']] = $this->static;
  }


  function extend_root($source){
    _engine_views::populate_root_object($source);
  }
  
  /* Loopback function for retrieving the generated code of a view
   */
  function execute ($source)
  {
    $exGET    = $_GET;             /* Store previous GET values */
    parse_str   ($source, $_GET);  /* Create a new _GET array from the source */
    $source   = array_keys($_GET);
    $source   = array_shift($source); 

    $subview  = new _($source);    /* Creates a new subview */
    $_buffer  = $subview->main();  /* Start the subview */
    $_GET     = $exGET;            /* Restore previous _GET array */
    return      $_buffer; 
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

  function call ($rpc_name, &$_STDIN, $options = '')
  {
    if (!isset($_STDIN)) $_STDIN = array();

    $context_path['system'] = '../core/rpc_calls/';
    $context_path['user']   = 'lib/rpc_calls/';

    /* TODO : checks for valid characters */
    $options  = explode(',', $options);
    $rpc_path = explode('.', $rpc_name);
    $context  = array_shift($rpc_path);
    $rpc_path = $context_path[$context].implode('.', $rpc_path).'.php';

    if (!is_file($rpc_path)) 
      die("RPC not found : '".$rpc_name."'");

    /* imports RPC code */
    require $rpc_path;

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
    $rpc_status = $function($this, $_STDIN, $rpc_response);

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
            $this->call($call, $_STDIN[$name]);
            break;
          
          /* composite string. May be made by a mix of quoted text and variables */ 
          case 'string' :
            eval('$_STDIN[$name] = '.$rule[1].';');
            if(substr($_STDIN[$name],0,1) != '"') $pre = '"';
            if(substr($_STDIN[$name],-1,1) != '"') $post = '"';
            $_STDIN[$name] = $pre.$_STDIN[$name].$post;
            eval('$_STDIN[$name] = '.$_STDIN[$name].';');
            break;
            
          case 'code' :
            ob_start();
            eval($rule[1].";");
            $_STDIN[$name] = ob_get_contents();
            ob_end_clean();
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
          "param : '".$name."', ".
          "required : '".$options['type']."', ".
          "found : '".gettype($_STDIN[$name])."').";
          
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

function register_attributes(&$webget, $attributes, $grab)
{
  $grab = array_merge($grab,
    array (
      'ondefine',
      'onflush',
      'nopaint',
      'boxing',
      'parent'));

 	foreach ($grab as $attribute_name) {    
 	  if(isset($attributes[$attribute_name])){
      @$webget->$attribute_name = $attributes[$attribute_name];
   	  unset($attributes[$attribute_name]);
   	}
 	}
 	@$webget->id = $attributes['id'];
 	$webget->attributes = $attributes;  
}

function array_get_nested(&$arr, $path, $separator = '.') 
{
  if(isset($arr)) if (!is_array($arr)) return false;

  $cursor = &$arr;
  $keys   = explode($separator, $path);

  foreach ($keys as $key) {
    if (isset($cursor[$key]))
      $cursor = &$cursor[$key];
    else    
      return false;
  }

  return $cursor;
}  
  

function clean_xml ($strin)
{
  $strout = null;
  
  for ($i = 0; $i < strlen($strin); $i++) {
    $ord = ord($strin[$i]);
    
    if (($ord > 0 && $ord < 32) || ($ord >= 127))
      $strout .= "&amp;#{$ord};";
 
    else {
      switch ($strin[$i]) {
        case '<': $strout .= '&lt;';   break;
        case '>': $strout .= '&gt;';   break;
        case '&': $strout .= '&amp;';  break;
        case '"': $strout .= '&quot;'; break;
        default: $strout  .= $strin[$i];
      }
    }
  }
  
  return $strout;
}

function _w($w)
{
  global $_;
  return $_->webgets[$w];
}

function _call($a, &$b, $c = NULL){
  global $_;
  return $_->call($a, $b, $c);
}

function uuidv4_gen(){
  $data = openssl_random_pseudo_bytes(16);
  
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
  
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
?>