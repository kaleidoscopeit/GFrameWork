<?php
session_start();
//header("HTTP/1.0 304 Not Modified");

$SG=$_SESSION;
// if user is not logged in redirect to login page
$admin=file('config/lock.php');
if(!$SG['bld'][$admin[1]])die();
// if exists include user localization file, else include english
if(@include("i18n/".$SG['bld'][$admin[1]]['lang'].".php"));
else include("i18n/en.php");

// include dinamicamente gli altri scripts su richiesta delle classi php istanziate
$J=array_shift(array_keys($_GET));
if($SG['js'][$J]){foreach($SG['js'][$J] as $k=>$v)include($k);}	
//else { if($SG['js'])foreach($SG['js'] as $k=>$v){include($k);}}
if($SG['debug']==0){$SG['js'][$J]=null;};
//$SG['js'][$J]=null;
$_SESSION=$SG;
?>