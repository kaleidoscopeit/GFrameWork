<?php
// scansiona la directory del progetto e ritorna un oggetto javascript corrispondente
function parse_dir($curr){
	global $enum,$out;
	$handle=opendir($curr);
	while (false!==($directory = readdir($handle))) {
		// Per ogni file trovato...ne verifica la specie
		if($directory != '.' && $directory != '..'){
			$active==1 ? print("},\n"):null;			
			echo $enum.':{lbl:"'.$directory.'"';$enum++;
			// Per ogni directory trovata... apre ricorsivamente il contenuto
			if(is_dir($curr.'/'.$directory)){echo ',';parse_dir($curr.'/'.$directory);}
			$active=1;
		}
	}
	echo '}';
	closedir($handle);
}

$enum=0;
echo 'PJTREE = ({tgt:"forms",';parse_dir('../data/forms');echo '})';
?>