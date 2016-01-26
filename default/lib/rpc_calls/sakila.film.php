<?php
/*
 * Retrieve a list of warehouse family or items or both by a given path as
 * filter
 */

$rpc = array(array(

/* filter */

'filter' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["filter"]',
)),

/* required range */

'range' => array (
  'type'     => 'array',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["range"]',
)),

/* database connection resource */

'db' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
      'variable:$_STDIN["db"]',
      'call:common.db_operations.connect'
))

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $qs = "SELECT SQL_CALC_FOUND_ROWS * FROM sakila.film "
       . (isset($_STDIN['filter']) ?
          "WHERE `title` LIKE '%" . $_STDIN['filter'] . "%'" : "")
      . (isset($_STDIN['range']) ?
          "LIMIT " . $_STDIN['range'][0] . "," . $_STDIN['range'][1] : "");


  $rs = mysql_query($qs, $_STDIN['db']['connection']);

  while ($_STDOUT['items'][] = mysql_fetch_assoc($rs));
  array_pop($_STDOUT['items']);

  $rs = mysql_query("SELECT FOUND_ROWS();", $_STDIN['db']['connection']);
  $_STDOUT['items']['maxlength'] = mysql_fetch_row($rs);
  $_STDOUT['items']['maxlength'] = $_STDOUT['items']['maxlength'][0];


  return TRUE;

});

?>
