<?php
/*
 * Add a new user
 */

$rpc = array(array(

/* new user id */

'uid' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["uid"]',
)),

/* new user password */

'password' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["password"]',
)),

/* user name */

'uname' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["uname"]',
)),

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  return FALSE;
});
?>