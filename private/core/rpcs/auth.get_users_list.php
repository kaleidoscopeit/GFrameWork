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

/* sort by field */
'sort' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
    'variable:$_STDIN["sort"]',
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
  if(isset($_STDIN["sort"])) {
    $sort = $_STDIN["sort"];
    unset($_STDIN["sort"]);
  }

/* check authentication  credentials */
_call("auth.engine." . $_STDIN["auth_engine"] . ".getulist", $_STDIN);

  if(isset($sort))
    usort($_STDIN, function ($a, $b) use ($sort) {
      return strcmp($a[$sort], $b[$sort]);
    });

  $_STDOUT = $_STDIN;
  return TRUE;
});

?>
