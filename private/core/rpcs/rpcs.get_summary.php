<?php
/*
 * Get the list of the rpcs of the current project
 */

$rpc = array (array (

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  // Scans for available views
  $dh = opendir('../core/rpcs/');

  while (false !== ($file = readdir($dh))) {
    if(is_file('../core/rpcs/' . $file)) $_STDOUT[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);

  }

  closedir($dh);
  $dh = opendir('rpcs/');

  while (false !== ($file = readdir($dh))) {
    if(is_file('rpcs/'. $file)) $_STDOUT[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
  }
  array_unique($_STDOUT);
  sort($_STDOUT);
  return TRUE;
});

?>
