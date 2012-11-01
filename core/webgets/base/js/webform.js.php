<?php
session_start ();	 
header('Content-type: text/Javascript');
$static = &$_SESSION['__gidestatic__'];
//include '../../../engine/main.php';	

?>
$_.env.user="<?php echo $static['auth']['user']['id'] ?>";
$_.env.uname="<?php echo $static['auth']['user']['name'] ?>";
$_.env.group="<?php echo implode(',',$static['auth']['user']['group']) ?>";
$_.env.domain="<?php echo $static['auth']['user']['domain'] ?>";
$_.env.client=Array();
$_.env.client.engine="<?php echo $static['client']['engine'] ?>";