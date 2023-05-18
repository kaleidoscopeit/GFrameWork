<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>

</td></tr><tr><td  valign="top" style="height:100%;">
	<div id="pjnav" style="overflow:auto;position:relative;height:100%;">
			<div id="pjnavhl" style="position:absolute;width:100%;height:20px;top:-20px;background-color:lightgray;"></div>
	</div>
</td></tr></table>
<script type="text/JavaScript">
<?php
// scansiona la directory del progetto e ritorna un oggetto javascript corrispondente
function parse_dir($curr){
	global $enum,$out;
	$handle=opendir($curr);
	while (false!==($directory = readdir($handle))) {
		// Per ogni file trovato...ne verifica la specie
		if($directory != '.' && $directory != '..'){
			$active==1 ? print("},\n"):null;			
			echo $enum.':{tgt:"'.$directory.'"';$enum++;
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
</script>