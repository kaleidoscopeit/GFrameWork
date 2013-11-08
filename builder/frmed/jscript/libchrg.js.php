libs = {};
<?php
// url di base delle librerie
$lib_URL = "dev-lib/";
// scansiona la directory delle librerie e ritorna il codice contenuto in ogniuna come una funzione
$handle=opendir($lib_URL);
// Per ogni directory di collezione di librerie trovata...
while (false!==($collection = readdir($handle))) {
	if($collection != '.' && $collection != '..'){
		// apre la collezione
		$collhdr=opendir($lib_URL.$collection);
		// per ogni libreria trovata ...
		while (false!==($library=readdir($collhdr))) {
			if($library != '.' && $library != '..' && !$bl[$collection.'/'.$library]){
				echo 'libs.'.$library.' = new function()';
				include($lib_URL.$collection.'/'.$library.'/bldcd.js');
				/*$data = file($lib_URL.$collection.'/'.$library.'/bldcd.js');
				foreach($data as $row){
					if($_SESSION['debug']==2) $out.=$row;
					// elimina i ritorni a capo
					else $out.=trim($row, "\t\n " );
				}		*/
				
			}
			echo ";";
		}		
	}
}

// se il livello di debug è inferiore a 2 ripulisce dai commenti il codice
//if($_SESSION['debug']<2)$out=preg_replace('/(\/\*).*?(\*\/)/','',$out);
// stampa l'uscita.. 
// l'uscita viene stampata dalla pagina js.php che richiama questa.
//echo $out;

closedir($handle);closedir($collhdr);
die;?>