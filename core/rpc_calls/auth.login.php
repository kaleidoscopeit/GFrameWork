<?php
/*
	* Authenticate the user against the server
 */

$rpc = array (array (

'user' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["user"]',
)),

'pass' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["pass"]',
)),

'domain' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["domain"]',
)),

'auth_engine' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_buffer["auth_engine"]',
    'variable:$_->settings["auth_engine"]',
))

),

/* rpc function */
 
function(&$_, $_buffer, &$_output) use (&$self)
{
  $user   = $_buffer['user'];
  $pass   = $_buffer['pass'];
  $domain = $_buffer['domain'];

  /* check authentication  credentials */  
  $_->call("system.auth.engine.".$_buffer["auth_engine"].".ckuser", $_buffer);

  if($_buffer[0]['signal'] == 'AUTH_CHECKUSER_ACCEPTED') 
    $_->static['auth']['user'] = $_buffer[1];
 	
  /* calls login custom function */
  if (is_callable($_->settings['auth_login_event'])) {
   		/* >>>>>>>>> ERROR BREAK POINT <<<<<<<<<<< */
  		 if (!$_->settings['auth_login_event']($_buffer, $_)){
  		   $_output[0] = array(
  		    'desc'   => "Authentication stack error!!!! Contact your sys admin.",
  		    'signal' => 'AUTH_LOGINSTACK_ERROR',
  		    'call'   => $self['name']);
      return FALSE;
    }
  }

  $_output[0] = array('signal' => $_buffer[0], 'call' => $self['name']);
  
  if($_buffer[0]['signal'] == 'AUTH_CHECKUSER_ACCEPTED')
    return TRUE;    
  else
    return FALSE;
});  

?>