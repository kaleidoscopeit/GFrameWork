<script type="text/JavaScript">
<?php
	// ritorna un oggetto contenente le informazioni necessarie a disegnare i pulsanti di ogni webget
	$out = 'wbgetTree=({';
	
	$lib_URL = "dev-lib/";
	$handle=opendir($lib_URL);
	// Per ogni directory di collezione di librerie trovata...
	while (false!==($collection = readdir($handle))) {
		if($collection != '.' && $collection != '..'){
			$out .= "".$collection.":{";
			// apre la collezione
			$collhdr=opendir($lib_URL.$collection);
			// per ogni libreria trovata aggiunge ad un array e lo riordina in ordine alfabetico
			while (false!==($library = readdir($collhdr))) {
				if($library != '.' && $library != '..'){
					$temp[file_get_contents($lib_URL.$collection.'/'.$library.'/tldef')] = "'".$library."':".substr(file_get_contents($lib_URL.$collection.'/'.$library.'/tldef'),2);
				}
			}
			ksort ($temp);
			$out .=  implode(',',$temp)."},";
			unset($temp);
		}
	}
	echo $out = rtrim($out,",")."})\n";
	closedir($handle);closedir($collhdr);

?>
	// aggiunge allo script di avvio le funzioni locali
	startupFunct.tobox = function(){
		OBJS.tobox = document.getElementById('tobox');
		OBJS.toboxFloatIco = document.createElement('img');
		OBJS.toboxFloatIco.style.position = 'absolute';
		OBJS.toboxFloatIco.style.left = '-40px';
		document.body.appendChild(OBJS.toboxFloatIco);
		tobox_builder();
	}

	// gestisce l'aggiunta di un nuovo webget al documento
	function tobox_addWebget(type,imgsrc){
		OBJS.toboxFloatIco.src = imgsrc;
		VARS.placeWait=1;
		VARS.placeType=type;
		
		document.body.onmousemove = function onMouseMove(event){
			OBJS.toboxFloatIco.style.left = event.clientX+1+'px';
			OBJS.toboxFloatIco.style.top = event.clientY+1+'px';
		}
		
		document.body.onmouseup = function onMouseUp(event){
			document.body.onmousemove = null;
			OBJS.toboxFloatIco.style.left = '-40px';
			VARS.placeWait=0;
		}
	}	
	
	
	// funzione che ritorna il codice che disegna un pulsante standard con un icona a scelta (dimensioni fisse)
	// --------------------------------------------------------------------------------------------------------------------------------------------------------
	function tobox_drawButton(image,libname,alt){

		return this.temp;
	}
	
	// gestore delle schede visualizzate
	// -----------------------------------------------
	function tobox_switcher(page){
		for(var sub in OBJS.tboxfold){
			sub != page ? OBJS.tboxfold[sub].style.visibility='hidden' : OBJS.tboxfold[sub].style.visibility='visible'; 
		}
	}
	
	// crea il contenuto delle schede con i pulsanti
	// ----------------------------------------------------------------
	function tobox_builder(){
		OBJS.tboxfold = {};		
		for(var sub in wbgetTree){
			VARS.lcount = 0
			
			OBJS.tboxfold[sub] = CrEl('div');
			OBJS.tboxfold[sub].style.cssText = "position:absolute;left:0px;top:0px;visibility:hidden;overflow:auto;height:100%;";

			for(var ssub in wbgetTree[sub]){
				VARS.lcount>4 ? VARS.lcount=0 : null;
				
				STUF.tr = CrEl('tr');

				STUF.img = CrEl('img');
				STUF.img.TAGTYPE=wbgetTree[sub][ssub][0];
				STUF.img.src='dev-lib/'+sub+'/'+ssub+'/icon.png';
				STUF.img.style.cssText='height: 30px;width: 30px;';
				STUF.img.onmousedown = function onMouseDown(event){
					tobox_addWebget(this.TAGTYPE,this.src,event);return false;
				}
				
				STUF.td=CrEl('td');
				STUF.td.width='40';
				STUF.td.align='center';
				STUF.td.appendChild(STUF.img);
				STUF.tr.appendChild(STUF.td);

				STUF.td=CrEl('td');
				STUF.td.align="left";
				STUF.td.style.cssText='font: 12px arial,sans;';
				STUF.td.innerHTML=wbgetTree[sub][ssub][1];
				
				STUF.tr.appendChild(STUF.td);

				STUF.table = CrEl('table');
				STUF.table.cellPadding=0;
				STUF.table.cellSpacing=0;
				STUF.table.width='100%';
				STUF.table.style.borderTop='1px solid black';
				STUF.table.appendChild(STUF.tr);
								
				OBJS.tboxfold[sub].appendChild(STUF.table);
				VARS.lcount++;
			}
			OBJS.tobox.appendChild(OBJS.tboxfold[sub]);			
		}
		tobox_switcher('COMMON');
	}
	
</script>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;background-color:#E5E5E5;"><tr><td valign="top" style="height:20px;">
<select size="0" onchange="tobox_switcher(this.value);" style="width:100%;border:1px solid grey;">
	<script type="text/JavaScript">
		for(var sub in wbgetTree){
			document.write('<option value="'+sub+'" label="'+sub+'">'+sub+'</option>');
		}		
	</script>
</select>
</td></tr><tr><td valign="top" style="height:100%;">
<div id="tobox" style="overflow:hidden;position:relative;height:100%;"></div>
</td></tr></table>
