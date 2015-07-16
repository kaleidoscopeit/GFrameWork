<?php
/*
	* HUB to get user information by a given auth engine
 */

$rpc = array (array (

/* searched user id */

'user' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["user"]',
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
  _call("auth.engine." . $_STDIN["auth_engine"] . ".getuinfo", $_STDIN);

  $_STDOUT = $_STDIN;
  return TRUE;
});

?>
