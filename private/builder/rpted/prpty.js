// aggiunge allo script di avvio le funzioni locali
startupFunct.prpty = function(){
	OBJS.prpty = document.getElementById('prpty');
	OBJS.evnts = document.getElementById('evnts');
	OBJS.mthod = document.getElementById('mthod');
	OBJS.rfncs = document.getElementById('rfncs');
	
}

	// gestore delle schede visualizzate
	// -----------------------------------------------
	function switcher(page,button){
		with(OBJS){
			prpty.style.visibility='hidden';
			evnts.style.visibility='hidden';
			mthod.style.visibility='hidden';
			rfncs.style.visibility='hidden';
			
			button.parentNode.parentNode.childNodes[1].firstChild.style.backgroundColor = '#E5E5E5';
			button.parentNode.parentNode.childNodes[3].firstChild.style.backgroundColor = '#E5E5E5';
			button.parentNode.parentNode.childNodes[5].firstChild.style.backgroundColor = '#E5E5E5';
			button.parentNode.parentNode.childNodes[7].firstChild.style.backgroundColor = '#E5E5E5';
			button.style.backgroundColor = 'lightgrey';
			
			switch(page){
				case 'prpty' :
					prpty.style.visibility='visible';
					
				break;

				case 'evnts' :
					evnts.style.visibility='visible';
				break;

				case 'mthod' :
					mthod.style.visibility='visible';
				break; 

				case 'rfncs' :
					rfncs.style.visibility='visible';
				break;
			}
		}	
	}

	// funzioni di acquisizione e aggiornamento del contenuto
	// --------------------------------------------------------------------------------
	
	// lancia l'aggiornamento delle proprietà visualizzate in base al webget corrente
	function prpty_update(){
		this.src = 'dev-lib/'+currItem.properties.TAGFAMILY+'/'+currItem.properties.TAGTYPE+'/prpts';
		callURL(src,'prpty_parse');	
	}

	// funzione che ricostruisce la pagina delle proprietà in base al webget selezionato
	function prpty_parse(){
		out = '<table border="0" >';
		for(var sub in data.properties){
			currField = data.properties[sub].split(':');
			temp = "'"+currField[4]+"',this.value"
			currValue = eval('currItem.properties.'+currField[4]);


			out += '<tr><td nowrap style="font:  12px arial,Sans;">'+currField[0]+' :&nbsp;&nbsp;</td><td width="100%" nowrap>';
			
			switch(currField[1]){
				case 'text' :
					
					out += '<input id="'+currField[4]+'" type="text" value="'+currValue+'" style="width:99%;border:1px solid grey;" onchange="updateDesign('+temp+');" />';
				break;
				gestione.xml
				case 'select' :
					out += '<select id="'+currField[4]+'" size="0" value="'+currValue+'" style="width:100%;border:1px solid grey;"></select>';
				break;

				case 'spin' :
					out += '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width="100%">'+
										'<input id="'+currField[4]+'" type="text" value="'+currValue+'" style="width:100%;border:1px solid grey;" onchange="updateDesign('+temp+');" />'+
									'</td><td>'+
										'<table cellpadding="0" cellspacing="0" border="0" style="border: 1px solid;"><tr><td>'+
											'<input type="button" value="+" style="font-size: 3px;width:5px;height:7px;" onclick="chSpin(\''+currField[4]+'\',\'up\')">'+
										'</td></tr><tr><td>'+
											'<input type="button" value="^" style="font-size: 3px;width:5px;height:8px;" onclick="chSpin(\''+currField[4]+'\',\'down\')">'+
										'</td></tr></table>'+
									'</td></tr></table>';									
				break;
				
				case 'url' :
					out += '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width="100%">'+
										'<input id="'+currField[4]+'" type="text" value="'+currValue+'" style="width:100%;border:1px solid" onchange="updateDesign('+temp+');"/>'+
									'</td><td>'+
										'<table cellpadding="0" cellspacing="0" border="0" style="border: 1px solid;"><tr><td>'+
											'<input type="button" value="..." style="font-size: 7px; height: 15px; width: 5px; ">'+
										'</td></tr></table>'+
									'</td></tr></table>';
					
				case 'bool' :
					out += '<input id="'+currField[4]+'" type="checkbox" '+(currValue=='true' ? 'checked' : '')+' onchange="updateDesign(\''+currField[4]+'\',this.checked);" >';
					
				break;
			}
			
			out += '</td></tr>';
		}	
		
		OBJS.prpty.innerHTML = out+'</table>';
		STUF = {};
		
		// prosegue creando il contenuto della tavola degli eventi
		STUF.maintable = CrEl('table');

		for(var sub in data.events){
			currField = data.events[sub].split(':');
			VARS.currValue = eval('currItem.properties.'+currField[2]);

			STUF.text = CrEl('input');
			STUF.text.type='text';
			STUF.text.value=VARS.currValue;
			STUF.text.style.cssText="width:100%;border:1px solid grey;";
			STUF.text.prpid=currField[2]; 
			STUF.text.onChange = function onChange(){updateDesign(this.prpid,this.value);}		

			STUF.butt = CrEl('input');
			STUF.butt.type='button';
			STUF.butt.value="..."
			STUF.butt.style.cssText="font-size: 7px; height: 15px; width: 5px; ";
			STUF.butt.chain = STUF.text;
			
			STUF.table=CrEl('table');
			STUF.tr=CrEl('tr');
			STUF.table.appendChild(STUF.tr);
			STUF.td = CrEl('td');
			STUF.td.width='100%';
			STUF.tr.appendChild(STUF.td);
			STUF.td.appendChild(STUF.text);
			STUF.td = CrEl('td');
			STUF.tr.appendChild(STUF.td);
			STUF.td.appendChild(STUF.butt);

			STUF.tr = CrEl('tr');
			
			STUF.td = CrEl('td');
			STUF.td.noWrap = 1;
			STUF.td.style.cssText="font:  12px arial,Sans;";	
			STUF.td.innerHTML = currField[0]+' :&nbsp;&nbsp;';
			STUF.tr.appendChild(STUF.td);

			STUF.td = CrEl('td');
			STUF.td.width='100%';
			STUF.td.noWrap = 1;
			STUF.td.appendChild(STUF.table);
			STUF.tr.appendChild(STUF.td);
			
			STUF.maintable.appendChild(STUF.tr);
			STUF.maintable.border='0';
			STUF.maintable.cellPadding='0';
			STUF.maintable.cellSpacing='0';
			out += '<tr><td nowrap style="font:  12px arial,Sans;">'+currField[0]+' :&nbsp;&nbsp;</td><td width="100%" nowrap>';
			
			switch(currField[1]){
				case 'jscode' :
					STUF.butt.onclick = function onClick(){
						VARS.currField=this.chain;
						window.open('jsedit.php','jsedit','width=500,height=400,location=0,menubar=0');
					}
				break;
				case 'phpcode' :
				break;
			}
		}	


		OBJS.evnts.removeChild(OBJS.evnts.firstChild);
		OBJS.evnts.appendChild(STUF.maintable);
	}
	
	// funzione che apre una piccola pagina contenente una lista di possibili valori per mselect
	// funzione
	
	// funzione che apre una piccola pagina contenente un selettore per files
	// funzione
	
	// funzione che incrementa o decrementa il valore nel campo spin mantenendo il suffisso
	function chSpin(field,dir){
		temp = document.getElementById(field);
		switch(dir){
			case 'up' :
				temp.value.search('px') != '-1' ? 
					temp.value = (temp.value.slice(0,temp.value.search('px'))-1+2)+'px' : 
					temp.value = (temp.value.slice(0,temp.value.search('%'))-1+2)+'%';
			break;
			
			case 'down' :
				temp.value.search('px') != '-1' ? 
					temp.value = temp.value.slice(0,temp.value.search('px'))-1+'px' : 
					temp.value = temp.value.slice(0,temp.value.search('%'))-1+'%';
			break;
		}
		temp.onchange();
	}
	
	// funzione che lancia l'aggiornamento della proprietà nella pagina di disegno	
	function updateDesign(prop,value){
		wbget_updPrpty(prop,value);
	}
	
	
	
