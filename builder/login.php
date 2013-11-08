<?php
// checks accepted language
$lang=explode(';',$_SERVER[HTTP_ACCEPT_LANGUAGE]);
$lang=explode('-',$lang[0]);
$lang=$lang[0];

session_start();
// se il file della password di amministrazione non esiste redirige al setup
if(!is_file('config/lock.php'))header("location: setup.php");
// if exists include user localization file, else include english
if(@include("i18n/".$lang.".php"));
else include("i18n/en.php");
// se è stato passato un uid valuta le funzioni di accesso
if($_POST['uid']){
	// se l'utente specificato è l'amministratore valuta la password nel file lock.php
	// diversamente valuta il file utenti passwd.php
	if($_POST['uid']=='admin'){
		// legge il file con la password di amministratore
		$admin=file('config/lock.php');
		// se la password è corretta sblocca l'accesso
		if(md5($_POST['pwd'])==$admin[2])$ul=true;
	} else {
		// quì sarà inserito il codice per valutare la password degli utenti
	}
	
	// se è sbloccato l'accesso registra l'utente in sessione diversamente
	// rivisualizza la pagina di accesso con un errore.
	if($ul==true){
		$_SESSION['bld'][$admin[1]]['uid']=$_POST['uid']; // user name under univocal server hash item
		$_SESSION['bld'][$admin[1]]['lang']=$lang;
		header("location: index.php");
	} else $E="Nome utente o password errate";
}
php?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>
			.text1{font:bold 12px arial,sans;text-align:center}
			input{border-width:1px;}

		</style>
		<script type="text/JavaScript" src="js.php"></script>
		<script type="text/JavaScript" src="js.php"></script>
	</head>
	<body style="position:absolute;overflow:hidden;height: 100%;margin: 0px;background-color:#E5E5E5;">
	<form method="post"  style="height:100%;">
		<table cellpadding="10" cellspacing="0" border="0" style="height:100%;">
		<tr>
			<td style="text-align:center;font:bold 20px arial,sans"><?php echo $lc_msg['lgin_0'];?></td>
		</tr>
		<tr>
			<td style="text-align:justify;font: 14px arial,sans;"><?php echo $lc_msg['lgin_1'];?></td>
		</tr>
		<tr>
			<td align="center" valign="top" style="height:100%">
				<table align="center"  cellpadding="5" class="text1">
				<tr>
					<td colspan="2"><br><br><?php echo $lc_msg['lgin_2'];?></td>
				</tr>
				<?php	if($E)echo '<tr><td colspan="2" style="color:red;">'.$E.'</td></tr>';?>
				<tr>
					<td align="right"><?php echo $lc_msg['lgin_3'];?></td>
					<td><input type="text" name="uid" value="<?php echo $_POST['uid']?>"></td>
				</tr>
				<tr>
					<td align="right"><?php echo $lc_msg['lgin_4'];?></td>
					<td><input type="password" name="pwd"></td>
				</tr>
				<tr>
					<td align="right" colspan="2"><input class="butt" type="submit" value="<?php echo $lc_msg['lgin_5'];?>"></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="font:11px arial,sans;text-align:center;">Gaia Web3.0 IDE - Builder <?php readfile('version.txt');?></td>
		</tr>
		</table>
	</form>
	</body>
</html>(