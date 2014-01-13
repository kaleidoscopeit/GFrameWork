<?php
/*
 * Retrieve a list of warehouse family or items or both by a given path as
 * filter  
 */

$rpc = array(array(

/* required range */

'query' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["query"]',
)),

/* database connection resource */

'db' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["db"]',
      'call:user.common.db_operations.connect'
))

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q="
        . urlencode($_STDIN['query']) . "&rsz=1";

  $json = file_get_contents($jsrc);
  $jset = json_decode($json, true);
  
  $_STDOUT[0] = $jset["responseData"]["results"][0]["url"];

  return TRUE;

});

?>

