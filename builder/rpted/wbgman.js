// funzioni che gestiscono i webget 
// -------------------------------------------------------------------------------------------------------------------------------------------------------


// funzione che seleziona e attiva uno dei webget nella pagina
function wbget_select(itemTree,mode){
	if(VARS.wbgsel==1){return;}
	
	// ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root
	this.tree = itemTree.split('/');
 	this.tree = this.tree.join('.en');

	// cattura l'oggetto JavaScript
	currItem = currArea.eval(this.tree);

	// aggiorna il box delle proprietà
	prpty_update();

	// inserisce il box per il controllo delle dimensioni
	currItem.core.insertBefore(OBJS.floater,currItem.core.firstChild);
	OBJS.floater.style.visibility="visible";
	
	mode=='nodrag' ? VARS.reszEN=0 : VARS.reszEN=1 ;

	// imposta una funzione ritardante che evita la selezione rimbalzata verso gli oggetti inferiori
	VARS.wbgsel = 1;
	window.setTimeout('VARS.wbgsel=0',100);
	
	// abilita le funzioni sulla pressione dei tasti
	document.onkeypress = function onKeyPress(e){
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		//else if (e.which) code = e.which;
		if(code==46)docmt_remWebget();
	}
	
	document.onmousedown = function omousedown(){
		if(VARS.wbgsel==1){VARS.wbgsel=0;return false;}
		document.onkeypress=null;
		document.onmousedown=null;
	}

	return false;
}

// funzione che lancia l'aggiornamento delle prprietà del webget specificato
function wbget_updPrpty(prptyName,prptyValue,vtree){
	libs[currItem.properties.TAGTYPE].updateProperty(prptyName,prptyValue);	
}

//funzioni per muovere il webget
function wbget_dragStart(e){
	// se il webget è in ridimensionamento la funzione viene annullata
	if(VARS.reszON==true){return false;};
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	
	//cattura l'offset di griglia
	VARS.itemX = currItem.properties.GEOMETRY[0].slice(0,currItem.properties.GEOMETRY[0].length-2);
	VARS.itemY = currItem.properties.GEOMETRY[1].slice(0,currItem.properties.GEOMETRY[1].length-2);
	
	// sottrae al numero con virgola ottenuto l'intero e moltiplica il rimanente per il passo di griglia
	VARS.offsetX = (VARS.itemX/CONF.gridStep-Math.floor(VARS.itemX/CONF.gridStep))*CONF.gridStep;
	VARS.offsetY = (VARS.itemY/CONF.gridStep-Math.floor(VARS.itemY/CONF.gridStep))*CONF.gridStep;
	
	VARS.currX = e.clientX;
	VARS.currY = e.clientY;
	VARS.dragON = true;
	
	// blocca l'accidentale riselezione di un altro webget
	VARS.wbgsel = 1;
}

function wbget_dragStop(){
	VARS.dragON=false;

	// aggiorna le informazioni sulla posizione nelle proprietà dell'oggetto corrente
	currItem.properties.GEOMETRY[0] = currItem.box.style.left;
	currItem.properties.GEOMETRY[1] = currItem.box.style.top;

	// imposta un timeout per riabilitare la possibilità di selezionare un altro webget
	window.setTimeout('VARS.wbgsel=0',100);	
	prpty_update();
}

function wbget_dragWbget(e){
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	with(VARS){
		// sposta il webget
		VARS.x= e.clientX-currX;
		VARS.y= e.clientY-currY;

		if(CONF.gridSnap==true){
			VARS.x = Math.floor((e.clientX-currX)/CONF.gridStep)*CONF.gridStep-offsetX;
			VARS.y = Math.floor((e.clientY-currY)/CONF.gridStep)*CONF.gridStep-offsetY;
		}
		//currItem.box.style.left = '100px';
		currItem.box.style.left = currItem.properties.GEOMETRY[0].slice(0,currItem.properties.GEOMETRY[0].length-2)-0+VARS.x+'px';
		currItem.box.style.top = currItem.properties.GEOMETRY[1].slice(0,currItem.properties.GEOMETRY[1].length-2)-0+VARS.y+'px';
	
		//	OBJS.dummy.value = VARS.x;
	}
}

// funzioni per ridimensionare il webget
function wbget_reszStart(dirctn,e){
	VARS.dirctn = dirctn;
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	with(VARS){
		// se le dimensioni del webget sono percentuali, vengono automaticamente convertite in scalari
		if(currItem.box.style.width.indexOf('%')>0){
			currItem.box.style.width = currItem.box.offsetWidth+'px';currItem.properties.GEOMETRY[2] = currItem.box.style.width;}
		if(currItem.box.style.height.indexOf('%')>0){
			currItem.box.style.height = currItem.box.offsetHeight+'px';currItem.properties.GEOMETRY[3] = currItem.box.style.height;}
	
		VARS.currX = e.clientX;
		VARS.currY = e.clientY;
	
		VARS.reszON = true;

		VARS.cig0 = currItem.properties.GEOMETRY[0].slice(0,currItem.properties.GEOMETRY[0].length-2);
		VARS.cig1 = currItem.properties.GEOMETRY[1].slice(0,currItem.properties.GEOMETRY[1].length-2);
		VARS.cig2 = currItem.properties.GEOMETRY[2].slice(0,currItem.properties.GEOMETRY[2].length-2);
		VARS.cig3 = currItem.properties.GEOMETRY[3].slice(0,currItem.properties.GEOMETRY[3].length-2);
		
		// blocca l'accidentale riselezione di un altro webget
		VARS.wbgsel = 1;
	}
}

function wbget_reszWbget(e){
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	
	with(VARS){
		// ottiene il valore di differenza della posizione del puntatore rispetto a quella iniziale
		VARS.x= e.clientX-currX;
		VARS.y= e.clientY-currY;

		if(CONF.gridSnap==true){
			VARS.x = Math.floor((e.clientX-currX)/CONF.gridStep)*CONF.gridStep-offsetX;
			VARS.y = Math.floor((e.clientY-currY)/CONF.gridStep)*CONF.gridStep-offsetY;
		}
		OBJS.dummy.value = x;
		switch(dirctn){
			case 'SE' :
				if(( cig2-0-x)>10){
					currItem.box.style.left = cig0-0+x+'px';
					currItem.box.style.width = cig2-0-x+'px';
				}
				if((cig3-0+y)>10){
					currItem.box.style.height = cig3-0+y+'px';
				}
			break;	
	
			case 'SW' :
				cig2-0+x > 10 ? currItem.box.style.width = cig2-0+x+'px' : null;
				cig3-0+y > 10 ? currItem.box.style.height = cig3-0+y+'px' : null;
			break;
			case 'NE' :
				if((cig3-0-y)>10){
					currItem.box.style.top = cig1-0+y+'px'
					currItem.box.style.height = cig3-0-y+'px';
				}
				if(cig2-0-x > 10){
					currItem.box.style.width = cig2-0-x+'px';
					currItem.box.style.left = cig0-0+x+'px';
				}
			break;
			case 'NW' :
				if(cig3-0-y>10){
					currItem.box.style.top = cig1-0+y+'px';
					currItem.box.style.height = cig3-0-y+'px';
				}
				cig2-0+x > 10 ? currItem.box.style.width = cig2-0+x+'px' : null;
				
			break;
		}
	}
}

function wbget_reszStop(){
	VARS.reszON=false;

	// aggiorna le informazioni sulla posizione nelle proprietà dell'oggetto corrente
	currItem.properties.GEOMETRY[0] = currItem.box.style.left;
	currItem.properties.GEOMETRY[1] = currItem.box.style.top;
	currItem.properties.GEOMETRY[2] = currItem.box.style.width;
	currItem.properties.GEOMETRY[3] = currItem.box.style.height;
	// imposta un timeout per riabilitare la possibilità di selezionare un altro webget
	window.setTimeout('VARS.wbgsel=0',100);	
	prpty_update();
}



// funzioni che gestiscon la creazione e l'eliminazione dei webget
// ------------------------------------------------------------------------------------------
function hilightItem(itemNum){
	if(preCreate==true){
		if(VARS.sel==1){return;}
		// cattura l'oggetto JavaScript
		currItem = eval('webgets.en'+itemNum);

		currItem.obj.appendChild(hilighter);
	
		// imposta una funzione ritardante che evita la selezione rimbalzata verso gli oggetti inferiori
		VARS.wbgsel = 1;
		window.setTimeout('sel=0',100);	
		return false;
	}
}

// funzioni per la gestione della griglia
function showGrid(flag){
	grid.style.visibility = (flag==true ? 'visible' : 'hidden');
	gridSnap= (flag==true ? 1 : 0);  
}




