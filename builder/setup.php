<?php
// if lock file is set exits from the script
if(is_file('config/lock.php'))die;

if($_POST){
	// verify dummy password issues
	if($_POST['pwd']!=$_POST['cfm'])$E="Le password digitate non coincidono";
	if($_POST['pwd']=="")$E="La password non può essere nulla";

	// if correct password is sets, writes data in the lock file  
	if(!$E){
		$f=fopen("config/lock.php","w"); // opens initial lock file
		fwrite($f,"<?php die;?>\n"); // writes a "die" for immediate exit  
		fwrite($f,md5(microtime())."\n"); // writes the microtime hash for univocal installation ID
		fwrite($f,md5($_POST['pwd'])); // writes admin password hash
		fclose($f);
		header("location: login.php");  
	}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>
			.text1{font:bold 12px arial,sans;text-align:center}
			.butt{border-width:1px; background-color:lightgrey}
		</style>
	</head>
	<body style="position:absolute;overflow:hidden;height: 100%;margin: 0px;background-color:#E5E5E5;">
	<form method="post" style="height:100%">
		<table cellpadding="10" cellspacing="0" border="0" style="height:100%;">
		<tr>
			<td style="text-align:center;font:bold 20px arial,sans">INSTALLAZIONE GAIA BUILDER</td>
		</tr>
		<tr>
			<td style="text-align:justify;font: 14px arial,sans;">Benvenuto! Per usare l'ambiente di sviluppo di Gaia Web3.0 IDE è necessario configurare l'utente che ammistrerà l'intera installazione. Accedere come utente amministratore da diritto al completo controllo è quindi necessario coservare i dati di accesso in un luogo sicuro. In ogni momento è possibile cambiare la password; è buona norma cambiarla regolarmente.</td>
		</tr>
		<tr>
			<td align="center" valign="top" style="height:100%">
				<table align="center"  cellpadding="5" class="text1">
				<tr>
					<td colspan="2">DIGITARE LA NUOVA PASSWORD</td>
				</tr>
				<?php	if($E)echo '<tr><td colspan="2" style="color:red;">'.$E.'</td></tr>';?>
				<tr>
					<td align="right">Utente</td>
					<td><input type="text" value="admin" disabled></td>
				</tr>
				<tr>
					<td align="right">Password</td>
					<td><input type="password" name="pwd"></td>
				</tr>
				<tr>
					<td align="right">Conferma</td>
					<td><input type="password" name="cfm"></td>
				</tr>
				<tr>
					<td align="right" colspan="2"><input class="butt" type="submit" value="Inizia"></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="font:11px arial,sans;text-align:center;">Gaia Web3.0 IDE - Builder Ver. 0.0.1 alpha</td>
		</tr>
		</table>
	</form>
	</body>
</html>