<?php
	/* Ritorna un oggetto contenente le informazioni necessarie a disegnare i pulsanti di ogni webget.
		Per ogni libreria è necessario creare un file tldef con una avente questa struttura:
		
		xx["$1","$2","$3"]
		
		xx = Numero formato da 2 cifre che viene usato per determinare l'ordine dei pulsani
		$1 = Etichetta del pulsante
		$2 = Nome della libreria
		$3 = Libreria obbiettivo(non usata e probabilmente verrà abolita)
		

		lbu		:	url della radice delle librerie
		hdl		:	handle della directory
		clt		:	collezione di librerie processate in un dato momento
		clh		:	handle della directory della collezione librerie attualmente aperta
		lby		:	nome della libreria attualmente processata
		tmp		:	variabile temporanea
		
	*/

	/* Starts output code */
	$out='wtr=({';
	/* Sets library root */
	$lbu="dev-lib/";
	$hdl=opendir($lbu);
	/* Per ogni directory di collezione di librerie trovata... */
	while (false!==($clt=readdir($hdl))){
		if($clt!='.'&&$clt!='..'){
			$out.="".$clt.":{";
			/* apre la collezione */
			$clh=opendir($lbu.$clt);
			/* per ogni libreria trovata aggiunge il nome ad un array */
			while(false!==($lby=readdir($clh))){
				/* se la voce è una directory e all'interno esiste il file di definizione */
				if($lby!='.'&&$lby!='..'){
					// if exists, include user localization file, else try to include english file
					if(@include($lbu.$clt.'/'.$lby."/i18n/".$SG['bld'][$admin[1]]['lang'].".php"));
					else @include($lbu.$clt.'/'.$lby."/i18n/en.php");
					/* aggiunge la nuova chiave dell'array con il nome = ad il contenuto di tldef */
					//$tmp[file_get_contents($lbu.$clt.'/'.$lby.'/tldef')]= 
						/*"'".$lby."':".rtrim(substr(file_get_contents($lbu.$clt.'/'.$lby.'/tldef'),2),"\n");*/
					$tmp[$lb_def[1]]="'".$lb_def[0]."':['".$lb_def[1]."','".$lb_def[0]."','ALL']";
				}
			}
			/* sistema in base alla stringa in tldef */ 
		//	ksort($tmp);
			/* forma la stringa di uscita unendo le librerie trovate */
			$out.= implode(',',$tmp)."},";
			/* vuota la variabile tmp */
			unset($tmp);
		}
	}
	/* stampa l'uscita */
	echo $out=rtrim($out,",")."});";
	closedir($hdl);closedir($clh);
?>