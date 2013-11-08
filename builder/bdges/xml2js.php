<?php
session_start();
// trasforma una pagina XML in una stringa corrispondente alla definizione di un oggetto Javascript

// Verifica che l'url richiesta non contenga richiami a nodi superiori
$exturl=explode('/',$_GET['url']);
foreach($exturl as $value){if($value=='..'){die('DIRECTORY INESISTENTE');}}
// se specificato, carica il file per traformarlo in un oggetto javascript
$xmlfile='../../../'.$_SESSION['bld']['root'].'/'.$_GET['file'];

// verifica che il file chiamato esista
is_file($xmlfile) or die ("PAGINA INESISTENTE");

// stampa l'inizio della stringa JSON
$out = '({';

// esegue il parsing del file con il nome uguale a quello indicato nella url
$xml_parser = xml_parser_create(); // crea l'handler del parser
xml_set_element_handler($xml_parser, "MWE_BSStartElem", "MWE_BSEndElem"); 	// specifica le funzioni di inizio e fine elemento
xml_set_processing_instruction_handler($xml_parser, "MWE_BSCodeBlock");
$xmldata = file_get_contents($xmlfile); // carica in una stringa il contenuto del file
if (!xml_parse($xml_parser, $xmldata, true)) 	// effettua il parsing richiamando ciclicamente le funzioni di inizio e fine TAG
die(sprintf("XML error in ".$xmlfile.": %s at line %d",
xml_error_string(xml_get_error_code($xml_parser)),
xml_get_current_line_number($xml_parser)));
xml_parser_free($xml_parser);unset($xmldata); 	// libera la memoria occupata

// chiude la stringa json
echo $out.'})';

// funzioni di parsing che generano l'oggetto javascript

function MWE_BSStartElem($parser, $TagType, $TagAttrs){

	global $out,$count;
	$count ++;
	$out .=	$count.':{TAGTYPE:"'.$TagType.'",';	
	//$out .=	$count.':{TAGSPCS:{TYPE:"'.$TagType.'"},';
	foreach($TagAttrs as $key => $value){
		// traduce alcuni caratteri mongoli che creano casini
		//$value=utf8_encode($value);
		$value=ereg_replace(urldecode('%0A'),'\r',$value);
		$value=ereg_replace(urldecode('%0D'),'\n',$value);
		$value=ereg_replace('°',urldecode('%C2%B0'),$value);
		$value=ereg_replace('<',urldecode('&lt;'),$value);
		$out .= $key.':"'.addslashes($value).'",';
	}
	
}

//---------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------

function MWE_BSEndElem($parser, $TagType){
	global $out;	
	$out = rtrim($out,',');
	$out .= "},";
}


// funzione da eliminare mantenuta per compatibilità
function MWE_BSCodeBlock(){
}
?>