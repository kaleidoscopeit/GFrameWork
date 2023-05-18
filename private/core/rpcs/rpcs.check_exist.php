<?php
/*
 * Get the list of the rpcs of the current project
 */

$rpc = array (array (

/* RPC URI */

'rpc_uri' => array (
  'type'     => 'string',
  'required' => true,
  'origin'   => array (
    'variable:$_STDIN["rpc_uri"]',
)),

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $status = 0;

  if(is_file('../core/rpcs/' . $_STDIN["rpc_uri"])) $status ++;
  if(is_file('rpcs/' . $_STDIN["rpc_uri"])) $status += 2;

  switch ($status) {
    case 0 : $_STDOUT = "NOT_EXISTS"; break;
    case 1 : $_STDOUT = "SYSTEM";     break;
    case 2 : $_STDOUT = "USER";       break;
    case 3 : $_STDOUT = "OVERRIDE";   break;
  }

  return TRUE;
});

?>

