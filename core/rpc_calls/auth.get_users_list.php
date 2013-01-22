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
    'variable:$_STDIN["filter"]',
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
  /* check authentication  credentials */  
  $_->call(
    "system.auth.engine.".$_STDIN["auth_engine"].".getulist",
    $_STDIN);

  $_STDOUT = $_STDIN;
  return TRUE;
});  

?>