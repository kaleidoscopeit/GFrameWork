<?php
/*
	* Authenticate the user against the server
 */

$rpc = array (array (

/* group name filter */
 
'filter' => array (
  'type'     => 'array',
  'required' => false,
  'origin'   => array (
    'variable:$_buffer["filter"]',
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
  /* check authentication  credentials */  
  $_->call(
    "system.auth.engine.".$_buffer["auth_engine"].".getulist",
    $_buffer);

  $_output = $_buffer;
  return TRUE;
});  

?>