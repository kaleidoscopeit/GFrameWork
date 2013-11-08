<?php
// if lock file doesn't exists resirect to setup page
if(!is_file('config/lock.php'))header("location: setup.php");
session_start();$SG=$_SESSION;
// Enable debug mode  
//$SG['debug']=1;
// if user is not logged in redirect to login page
$admin=file('config/lock.php');
if(!$SG['bld'][$admin[1]])header("location: login.php");
// if exists include user localization file, else include english
if(@include("i18n/".$SG['bld'][$admin[1]]['lang'].".php"));
else @include("i18n/en.php");
// se non è stato specificato un progetto imposta quello di default
// con questo sistema, nel caso della versione con gestore progetto,
// non devo fare casini per integrarlo, ma basta solo che non sia presente
if(!$SG['bld']['root'])$SG['bld']['root']='default';

// ritorna una pagina differente in base al nome della chiave della
// prima variabile passata in $_GET
$info=array_keys($_GET);

switch($info[0]){
	case '1': include('pjman/config.php');break;
	case '2': include('pjman/frmmg.php');break;
	case '3': include('pjman/rptmg.php');break;
	case '6': include('pjman/dialog/nfrm.php');break;
	// Editor dei forms e finestre accessori
	case '10': include('frmed/frmed.php');break;
	case '11': include('frmed/tobox.php');break;
	case '12': include('frmed/prpty.php');break;
	case '13': include('frmed/dctre.php');break;
	case '14': include('frmed/dlogs/jsedit.php');break;
	case '15': include('frmed/dlogs/phpedit.php');break;
	case '16': include('frmed/dlogs/envedit.php');break;
	case '17': include('frmed/topan.php');break;
	// sorgenti specializzate di informazioni
	case '100': include('bdges/dsn2js.php');break;
	case '101': include('bdges/lib2js.php');break;
	default: include('pjman/pjman.php');break;
}

$_SESSION=$SG;
?>
