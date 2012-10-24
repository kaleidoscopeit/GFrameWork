<?php
class _
{
   
  function __construct()
  {
    /* get the path of called object if not previously declared
       (appens when the view is called in a nested execution) */
    $source = array_keys($_GET);
    $source = array_shift($source);           

    /* redirect to the default page if the path of called object is malformed */
    if (strpos ($source, '../') >- 1 OR $source == "")   
      header("location: ?views/default");  

    /* extract the type of object called ( must be the first part ) */
    $source = explode('/', $source);

    $this->CALL_OBJECT = array_shift($source);
    $this->CALL_SOURCE = implode('/', $source);
    $this->CALL_UUID   = hash('crc32',$this->CALL_SOURCE);
  }                        
  

  /* apply the security policy if required and act as hub for various 
     sub funcitions depending by the called object */
  function main()
  {
    // prints start microtime (for benchmarking purpuose)
    //$microtime = microtime (true);                          

    /* private loopback to the default name of the gide class */
    $_=$this;

    /* try to set the session longer and start it */
    ini_set('session.gc_maxlifetime', 60*60*8);                
    session_start();  

    /* imports GIDE static data from browser session */
    $this->static = &$_SESSION['__gidestatic__'];
    
    /* imports the project configuration file and 
       the configuration database engine */
    require "config.php";                                    

    switch ($this->CALL_OBJECT) { 
      case 'views' :
        require_once '../core/engine/views.php';

        if (!$_->call('system.auth.check',$_buf))
          $this->CALL_SOURCE = $this->settings['auth_login_page'];
        return _engine_views::build();
        break;

      case 'reports' :
        require_once '../core/engine/reports.php';

        if (!$_->call('system.auth.check',$_buf))
          $this->CALL_SOURCE = $this->settings['auth_login_page'];
        return _engine_reports::build();
        break;
        
      case 'call' :
        $response[1] = unserialize($_POST['b']);
        $flags       = isset($_POST['h']) ? $_POST['h'] : '';
        $path        = str_replace('/', '.', $this->CALL_SOURCE);
        $response[0] = $this->call($path, $response[1], $flags);
        echo           serialize($response);
        break;
        
      case 'lib' :
        require $this->library_path.'../core/engine/djl.php';
        return  _engine_djl::get();
        break;
    }
    
    /* dump G-FRAMEWORK static data to browser session */                        
    $_SESSION['__gidestatic__'] = $this->static;
  }

  /* Loopback function for retrieving the generated code of a view
   */
  function execute ($source)
  {
    $exGET    = $_GET;             /* Store previous GET values */
    parse_str  ($source, $_GET);   /* Create a new _GET array from the source */
    $subview  = new _();           /* Creates a new subview */
    $_buffer  = $subview->main();  /* Start the subview */
    $_GET     = $exGET;            /* Restore previous _GET array */
    return      $_buffer; 
  }
  

  function call_new ($rpc_name, &$_buffer, $options = '')
  {
    if (!isset($_buffer)) $_buffer = array();

    $context_path['system'] = '../core/rpc_calls/';
    $context_path['user']   = 'lib/rpc_calls/';

    /* TODO : checks for valid characters */
    $options  = explode(',', $options);
    $rpc_path = explode('.', $rpc_name);
    $context  = array_shift($rpc_path);
    $rpc_path = $context_path[$context].implode('.', $rpc_path).'.php';

    if (!is_file($rpc_path)) return 'false'; 
      //die("RPC not found : '".$rpc_name."'");

    /* imports RPC code */


//    print_r($this->rpcs);
  //  echo "////////////";
  //  if (!isset($this->rpcs[$rpc_name])) {
      require $rpc_path;

      $this->rpcs[$rpc_name]         = $rpc;
      $this->rpcs[$rpc_name]['name'] = $rpc_name;
   // } 

    $self      = $this->rpcs[$rpc_name];
    $function  = $this->rpcs[$rpc_name][1];    

    /* checks inputs parameters rules */
    $rpc_check = $this->_call_param_check($_buffer, $this->rpcs[$rpc_name]);

    if($rpc_check !== true) {
      $_buffer['STDERR'] = $rpc_check;
     return false;
    }
 
    /* call the RPC */
    $rpc_status = $function($this, $_buffer, $rpc_response);

//    echo $rpc_name. '    '.$rpc_status."\n";
    
    /* FAILURE BEHAVIOUR */

    /* in case of FAILURE do following dependig by choosen output option */
    if ($rpc_status == false) {
      
      /* directly die */
      if (in_array('die',$options))
        die("RPC '".$rpc_name."' failed.");

      /* try to use default error dialog */
      if (in_array('dialog',$options) && $_->ROOT)
        $_->ROOT->system_error(print_r($rpc_response['error']));

      /* else prints the error to the stdout (default behaviour)*/          
      else print_r($rpc_response['error']);
    }


    /* OUTPUT OPTIONS */

    /* put results in a custom labeled array */
    if ($label = array_search('label',$options))
      $output_buffer[$options[$label+1]] = $rpc_response;

    /* put results in a labeled array with the class name as label */      
    if (in_array('path',$options))
      $output_buffer[$class->name] = $rpc_response;

    /* merge input buffer with the call response */    
    if (in_array('stack',$options)) {
      if (is_array($rpc_response) === FALSE) 
        $rpc_response = array($rpc_response);
        
      $_buffer = array_merge($_buffer, $rpc_response);
    }

    /* or returns only the call response */          
    else $_buffer = $rpc_response;


    /* CALL END */
  
    return $rpc_status;    
  }



 function _call_param_check(&$_buffer, $call)
 {
   global $_;

  foreach ($call[0] as $name => $options) {

    /* try to fetch the source value depending by 'origin' rules */
    foreach ($options['origin'] as $rule) {
      $rule = explode(':', $rule);

      switch ($rule[0]) {
        case 'variable' :
          eval('if(isset('.$rule[1].'))$_buffer[$name] = '.$rule[1].';');
          break;
          
        case 'call' :
          $this->call($rule[1], $_buffer[$name]);
          break;
          
        case 'string' :
          eval('$_buffer[$name] = '.$rule[1].';');
          if(substr($_buffer[$name],0,1) != '"') $pre = '"';
          if(substr($_buffer[$name],-1,1) != '"') $post = '"';
          $_buffer[$name] = $pre.$_buffer[$name].$post;
          eval('$_buffer[$name] = '.$_buffer[$name].';');
          break;
          
        case 'code' :
          ob_start();
          eval($rule[1].";");
          $_buffer[$name] = ob_get_contents();
          ob_end_clean();
          break;
      }

      /* breaks if one of the origin gives a result */
      if($_buffer[$name] !== null) break;
    }

    /* check strict rules (IT'S A VERY VERY UGLY CODE)*/
    if (!isset($_buffer[$name])) {
      if ($options['required'] == true)
        $bad = "Cannot get required param -> '".$name."'.";
    }
 
    else {
      $bad_text = "Required param type not match (".
          "param : '".$name."', ".
          "required : '".$options['type']."', ".
          "found : '".gettype($_buffer[$name])."').";
          
      if (gettype($_buffer[$name]) != $options['type'])

        /* not alwais a number is passed as it is, but may be passed as string
         * this code verify, in case of a numeric value is required but a
         * string is recognized, if it's really a numberic value */ 
        if(gettype($_buffer[$name]) == 'string' &&
           ($options['type'] == 'float' ||
            $options['type'] == 'bool' ||
            $options['type'] == 'integer')) {

          /* needs a cast to reconize the correct type */
          switch($options['type']) {
            case 'float' :
            case 'bool' :
            case 'integer' :
              if(!is_numeric($_buffer[$name])) $bad = $bad_text;
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
        'param'  => $_buffer);
    }
  }

  return true;
 }  
 
 
 
  /* Wrapper for classes with some improvements.
   *  
   *
   * $path        : 'path' of the class+function
   *
   * $_buffer      :  array to be used as buffer, contains the input values 
   *                 when the macro is called and the final content depends
   *                 on $output switches (default : only the result)
   *
   * $options     : list of switches
   *
   *   - stack  ->  the result of current macro will added to the $_buffer; 
   *                every previous similar values will be overwritten
   *   - path   ->  the result of current call will added to the $_buffer
   *                in a sub array labeled as the path of the call
   *   - label  ->  the result of current call will nested in a sub-array
   *                with a key named as the next value in the $options array
   *   - die    ->  on error (function report 'false') print a message and die
   *
   *
   * returns an integer value, the programmer has the freedom to choose 
   * right response.
   */    
  
  function call($path, &$_buf, $options = '')
  {
    /* workaroud for keep compatibility with old call method */ 
    $test = $this->call_new($path, $_buf, $options);

    if($test !== 'false') return $test;

    if (!isset($_buf)) $_buf = array();

    $context_path['system'] = '../core/rpc_calls/';
    $context_path['user']   = 'lib/';

    /* TODO : checks for valid characters */
    $options  = explode(',', $options);
    $path     = explode('.', $path);
    $function = array_pop($path);                                              
    $call     = implode('.', $path);
    $context  = array_shift($path);
    $class    = array_pop($path);
    $path     = $context_path[$context].implode('/', $path).'/'.$class;

    if (!is_file($path.".cl.php"))
      die("Called class not found : '" . $call . "." .$function . "'");

    /* construct the class and store it if not already made */
    if (!isset($this->calls[$call])) {
      require_once $path.".cl.php";

      $this->calls[$call]        = new $class();
      $this->calls[$call]->_path = $call;
      
    } 

    $this->calls[$call]->_subject = $function;

    if (isset($this->settings['calls'][$call]))
      foreach ($this->settings['calls'][$call] as $key => $value )
        $this->calls[$call]->$key = $value;

    $return = $this->calls[$call]->$function($this, $_buf, $stdout);

    if ($return===false) {
      if (in_array('die',$options))
        die('\'macro.'.$class->_path.'.'.$class->_subject.'\' failed.' );
        
      if (in_array('dialog',$options))
        if ($_->webgets['root'])
          $_->webgets['root']->system_error(print_r($stdout['error']));
          
        else print_r($stdout['error']);
    }

    if ($label = array_search('label',$options))
      $outbuf[$options[$label+1]] = $stdout;
      
    if (in_array('path',$options))
      $outbuf[$class->path] = $stdout;
    
    if (in_array('stack',$options)) {
      if (is_array($stdout) === FALSE)
        $stdout = array($stdout);
      $outbuf = array_merge($_buf, $stdout);
    }
          
    else $outbuf = $stdout;

    $_buf = $outbuf;

    return $return; 
  }

/*  function set_webget_default($webget){
    if (!$webget->default) return;

    foreach ($webget->default as $property => $options)
      foreach ($options as $value)
        if ($value != null && !$webget->$property)
          $webget->$property = $value;
  }*/
  
}

function &array_get_nested(&$arr, $path, $separator = '.') 
{
  if (!is_array($arr)) return false;

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

?>