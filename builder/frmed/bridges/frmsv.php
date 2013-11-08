<?php
session_start();

// verifica che l'url del documento su cui salvare non contenga riferimento a rami superiori
$filename="../../../../".$_SESSION['bld']['root']."/".$_POST['url'];

if(strpos($_POST['url'],'../')!=false){die;}

// elimina i backslash dovuti al trasporto da client al server
//$somecontent=ereg_replace('&lt;','',$somecontent);
$somecontent=urldecode($_POST['data']);

// Verifica che il file esista e sia riscrivibile
if(is_writable($filename)){

	// In questo esempio apriamo $filename in append mode.
	// Il puntatore del file è posizionato in fondo al file
	// è qui che verrà posizionato $somecontent quando eseguiremo fwrite().
	if(!$handle=fopen($filename,'w')){

		echo "fault.png|Non si riesce ad aprire il file ($filename)";
		exit;
	}
	// Scrive $somecontent nel file aperto.
	if(fwrite($handle,$somecontent)===FALSE){
		echo "fault.png|Non si riesce a scrivere nel file ($filename)";
		exit;
	}
	echo "saveok.png|Il file &egrave; stato salvato correttamente.";    
	fclose($handle);
}else{   
	echo "fault.png|Il file non esiste o non &egrave; accessibile";
}
?>
