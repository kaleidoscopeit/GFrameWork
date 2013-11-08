<?php
if(!$SG)die;
// esce se uno dei due parmetri dell'url passati richiama una directory superiore oppure sono vuoti
if(strpos($_GET['c'],'..') || strpos($_GET['l'],'..') ||	!$_GET['c'] ||	!$_GET['l'])die;
// carica i dati dell'internazionalizzazione della libreria
$url='dev-lib/'.$_GET['c'].'/'.$_GET['l'].'/';
// if exists, include user localization file, else try to include english file
if(@include($url."i18n/".$SG['bld'][$admin[1]]['lang'].".php"));
else @include($url."i18n/en.php");

$SG['js']=null;

// execute different action
switch($_GET['f']){
	case 'i' :
		header("Content-type: image");
		@readfile($url.'/icon.png');
		break;
	case 'd' :
		if(strpos($_GET['o'],'..'))die;
		include($url.'/'.$_GET['o'].'.php');
		break;		
	default :
		include($url.'/prpts');
		break;
}
$_SESSION=$SG;
?>