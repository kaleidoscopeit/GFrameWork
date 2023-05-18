<?php
/*
 * Get the list of the views of the current project
 */

$rpc = array (array (

/* filter result data */

'filter' => array (
  'type'     => 'string',
  'required' => false,
  'origin'   => array (
      'variable:$_STDIN["filter"]',
)),

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  // Scans for available views
  $dh = opendir('views/');

  while (false !== ($file = readdir($dh))) {
    //if($file=='.' AND $file=='..') continue;
    if(is_file("views/" . $file . "/_this.xml")) $_STDOUT[] = $file;
  }

  if($_STDIN["filter"] != "")
    $_STDOUT = array_filter($_STDOUT, function($var) use (&$_STDIN) {
      if(strpos($var, $_STDIN["filter"]) !== false) return $var;
    });

  sort($_STDOUT);
  return TRUE;
});

?>
