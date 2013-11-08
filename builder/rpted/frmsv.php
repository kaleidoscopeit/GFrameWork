<?php
///echo $_POST['data'].'    '.$_POST['url']; die;
	// riconverte i codici per i caratteri speciali nei rispettivi caratteri
	

	$filename="../data/".$_POST['url'];
	if(strpos($_POST['url'],'../')!=false){die;}
	
	$somecontent=rawurldecode($_POST['data']);
	// Verifica che il file esista e sia riscrivibile
	if(is_writable($filename)){
		// In questo esempio apriamo $filename in append mode.
		// Il puntatore del file è posizionato in fondo al file
		// è qui che verrà posizionato $somecontent quando eseguiremo fwrite().
		if(!$handle=fopen($filename,'w')){
			echo "Non si riesce ad aprire il file ($filename)";
			exit;
		}
		// Scrive $somecontent nel file aperto.
		if(fwrite($handle,$somecontent)===FALSE){
			echo "Non si riesce a scrivere nel file ($filename)";
			exit;
		}
		echo "Riuscito, scritto ($somecontent) nel file ($filename)";    
		fclose($handle);
	}else{   
		echo "Il file $filename non è accessibile";
	}
?>