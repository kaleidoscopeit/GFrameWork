<?php
/*
 * RPC description
 */

$rpc = array(array(

/* argument description */

'[argument_name]' => array (
  'type'     => '[variable_type]',
  'required' => (true||false),
  'origin'   => array (
      'variable:$_STDIN["<<<argument_name>>>"]',
      '[origin_type]:[origin_code]',
)),

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
	/* RPC Code */

  return TRUE;
});























?>

