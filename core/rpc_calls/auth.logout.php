<?php
/*
 * destroy session
 */

$rpc = array(array(

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
	unset($_->static['auth']);
	return TRUE;
});
?>