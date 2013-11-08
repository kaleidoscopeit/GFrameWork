<?php
// se specificato, carica il file per traformarlo in un oggetto javascript
$xmlfile = '../data/'.$_GET['file'];

// verifica la correttezza sintattica del parametro passato (verifica che non sia passato un
// un nome con delle slash che potrebbero sottintendere dei tentativi per)
strpos($xmlfile,'../') ? die ('PAGINA INESISTENTE') : null ;

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
	$out .=	$count.':{TAGNUM:"'.$count.'",TAGTYPE:"'.$TagType.'",';
	foreach($TagAttrs as $key => $value){
		$out .= $key.':"'.$value.'",';
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