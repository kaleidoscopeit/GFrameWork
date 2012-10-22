<?php
session_start ();																		// starts session 
//$_->static = $_SESSION['__gidestatic__'];								// retrieves the session data
include '../../../engine/main.php';															// include main.php to use some useful functions

?>
$_.env.user="<?php echo $_->static->auth->user->id ?>";
$_.env.uname="<?php echo $_->static->auth->user->name ?>";
$_.env.group="<?php echo $_->static->auth->user->group ?>";
$_.env.domain="<?php echo $_->static['auth']->user->domain ?>";
$_.env.client=Array();
$_.env.client.engine="<?php echo $_->static['client']['engine'] ?>";