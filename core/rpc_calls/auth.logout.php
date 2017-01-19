<?php
/*
 * destroy session
 */

$rpc = array(array(

),

/* rpc function */

function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
	$_->static = NULL;
	return TRUE;
});
?>
