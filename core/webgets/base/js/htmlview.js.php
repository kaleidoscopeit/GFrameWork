<?php
session_start ();	 
header('Content-type: text/Javascript');
die;
include "../../../engine/main.php";

ini_set('display_errors',1); 
// error_reporting(E_ALL);

$_       = new _();
$buffer  = $_->main(); 
$debug   = $_->settings['formatted_output'];

$static = &$_SESSION[$this->settings['app-uuid']];
//include '../../../engine/main.php';	

?>
$_.env.user="<?php echo $static['auth']['user']['id'] ?>";
$_.env.uname="<?php echo $static['auth']['user']['name'] ?>";
$_.env.group="<?php echo implode(',',$static['auth']['user']['group']) ?>";
$_.env.domain="<?php echo $static['auth']['user']['domain'] ?>";
$_.env.client=Array();
$_.env.client.engine="<?php echo $static['client']['engine'] ?>";