<?php
/*
 * Get the list of the rpcs of the current project
 */

$rpc = array (array (

/* View URI */

'view_uri' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["view_uri"]',
)),

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
    if(is_file("views/" . str_replace('/', '.', $_STDIN["view_uri"]) . "/_this.xml")) return TRUE;
  //reports.labels.crono.etcode-01_r1_single
  //reports.labels.crono.etcode-01_r1_singlea
  return FALSE;
});

?>

