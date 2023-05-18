<?php
session_start();
// Questa pagina ritorna un oggetto javascript (JSON) corrispondente alla struttura della direcroty
// contenente il progetto in lavorazione ovvero tutto quello che è conenuto in "%site_root%/data".
// Alcune directory verranno escluse dalla lista in quanto non necessarie alla gestione del progetto.
// Questa pagina sarà un giorno protetta da un login come tutto il tool di sviluppo. 
 
// Verifica che l'url richiesta non contenga richiami a nodi superiori
$exturl=explode('/',$_GET['url']);
foreach($exturl as $value){if($value=='..'){die('DIRECTORY INESISTENTE');}}
$dir='../../../'.$_SESSION['bld']['root'].'/'.$_GET['url'];

// verifica che il file chiamato esista
is_dir($dir) or die ("node INESISTENTE");

// analizza la struttura ad albero
/*function parse_dir($curr,$enum){
	$handle=opendir($curr);
	while (false!==($node=readdir($handle))) {
		// lista di url esclusi. Se viene trovato la stringa modello termina
		if((strpos($curr.'/'.$node,'../../'.$_SESSION['bld']['root'])>-1 ||
			strpos($curr.'/'.$node,'../../themes')>-1) &&
			$node != '.' && $node != '..'
		){
			$active==1 ? print("}\n"):null;			
			echo ','.$enum.':{tgt:"'.$node.'",mime:"'.(filetype($curr.'/'.$node)=="dir"?'inode':getext($node)).'"';$enum++;
			// Per ogni node trovata... apre ricorsivamente il contenuto
			if(is_dir($curr.'/'.$node)){parse_dir($curr.'/'.$node,$enum);}
			$active=1;
		}
	}
	if($active==1)echo '}';
	closedir($handle);
}*/


function parse_dir($curr,$enum){
	global $data;
	$handle=opendir($curr);
	while (false!==($node=readdir($handle))) {
		// lista di url esclusi. Se viene trovato la stringa modello termina
		if((strpos($curr.'/'.$node,'../../'.$_SESSION['bld']['root'])>-1 ||
			strpos($curr.'/'.$node,'../../themes')>-1) &&
			$node != '.' && $node != '..'
		){
			$active==1 ? $data.="]\n":null;
			$data.=',["'.$node.'","'.(filetype($curr.'/'.$node)=="dir"?'inode':getext($node)).'"';$enum++;
			// Per ogni node trovata... apre ricorsivamente il contenuto
			if(is_dir($curr.'/'.$node)){parse_dir($curr.'/'.$node,$enum);}
			$active=1;
		}
	}
	if($active==1)$data.=']';
	closedir($handle);
}

// restituisce solo l'estensione del file
function getext($f){
	$f=explode('.',$f);
	array_shift($f);
	return array_pop($f);
}
// lancia il parsing della directory scelta
parse_dir($dir,0);

// risponde con l'aggetto JSON
//echo '({tgt:"'.$_GET['url'].'",mime:"inode"';parse_dir($dir,0);echo '})';
//echo '["'.$_GET['url'].'","inode"';parse_dir($dir,0);echo ']';
//echo '[';ltrim(parse_dir($dir,0),",");echo ']';
echo '['.ltrim($data,",").']';
?>