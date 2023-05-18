libs = {};
<?php
// scansiona la directory delle librerie e ritorna il codice contenuto in ogniuna come una funzione
$lib_URL = "dev-lib/";
$handle=opendir($lib_URL);
// Per ogni directory di collezione di librerie trovata...
while (false!==($collection = readdir($handle))) {
	if($collection != '.' && $collection != '..'){
		// apre la collezione
		$collhdr=opendir($lib_URL.$collection);
		// per ogni libreria trovata ...
		while (false!==($library = readdir($collhdr))) {
			if($library != '.' && $library != '..'){
				echo "libs.".$library.' = new function()';
				$data = file($lib_URL.$collection.'/'.$library.'/bldcd.js');
		
				foreach($data as $row){
					echo trim (ereg_replace("(/\*)(.)+(\*/)",'',$row), "\t\n" );
				}
				echo "\n";
			}
			
		}
	}
}
closedir($handle);closedir($collhdr);

?>