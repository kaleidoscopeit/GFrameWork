<?php
/*
  * Bypass all input data to the output
 */

$rpc = array (array (

),

/* rpc function */ 
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
  $_STDOUT = $_STDIN;
  return true;
});  

?>