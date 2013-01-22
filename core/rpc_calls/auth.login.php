<?php
/*
	* Authenticate the user against the server
 */

$rpc = array (array (

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["user"]',
)),

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["pass"]',
)),

'domain' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["domain"]',
)),

'auth_engine' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["auth_engine"]',
    'variable:$_->settings["auth_engine"]',
))

),

/* rpc function */
 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $user   = $_STDIN['user'];
  $pass   = $_STDIN['pass'];
  $domain = $_STDIN['domain'];

  /* check authentication  credentials */  
  $_->call("system.auth.engine.".$_STDIN["auth_engine"].".ckuser", $_STDIN);

  if($_STDIN[0]['signal'] == 'AUTH_CHECKUSER_ACCEPTED') 
    $_->static['auth']['user'] = $_STDIN[1];
 	
  /* calls login custom function */
  if (is_callable($_->settings['auth_login_event'])) {
   		/* >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<< */
  		 if (!$_->settings['auth_login_event']($_STDIN, $_)){
  		   $_STDOUT[0] = array(
  		    'desc'   => "Authentication stack error!!!! Contact your sys admin.",
  		    'signal' => 'AUTH_LOGINSTACK_ERROR',
  		    'call'   => $self['name']);
      return FALSE;
    }
  }

  $_STDOUT[0] = array('signal' => $_STDIN[0], 'call' => $self['name']);
  
  if($_STDIN[0]['signal'] == 'AUTH_CHECKUSER_ACCEPTED')
    return TRUE;    
  else
    return FALSE;
});  

?>