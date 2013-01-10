<?php
/*
 * Group based match filter. Accepts an array of group and returns an array
 * of group matching the ones of the current user seats
 */

$rpc = array(array(

/* group names filter */

'groups' => array (
  'type'     => 'array',
  'required' => true,
  'origin'   => array (
      'variable:$_buffer["groups"]',
)),

),

/* rpc function */
  
function(&$_, $_STDIN, &$_STDOUT) use (&$self)
{
	if (count($_->static['auth']['user']['group']) > 0 and
	    count($_buf['groups']) > 0)
		$_out = array_intersect($_->static['auth']['user']['group'],
		                        $_buf['groups']);

	return TRUE;
});
?>
