// funzioni che gestiscono gli spazi di lavoro virtuali
// ----------------------------------------------------------------------
//
// aggiunge uno spazio di lavoro virtuale
function wkspc_addNew(url){
	//ottiene il nome del file richiesto
	VARS.title = url.substr(url.lastIndexOf('/')==-1 ? 0 : url.lastIndexOf('/'));
	VARS.title = VARS.title.substr(0,VARS.title.length-4);
	 
	OBJS.dspcSwitcher = document.getElementById('dspcSwitcher');
	OBJS.area = document.createElement('div');
	OBJS.area.id = 'area'+VARS.areaEnum;
	OBJS.area.style.cssText =   "position:absolute;overflow:hidden;left:0px;top:0px;border-right:1px solid;border-bottom:1px solid;";
	OBJS.designspace.appendChild(OBJS.area);
	designAreas[VARS.areaEnum] = {url:url,vspace:'',root:''};	
	currArea = designAreas[VARS.areaEnum];
	currArea.vspace = OBJS.area;	
	currArea.root = {core: currArea.vspace};	
	OBJS['temp'] = document.createElement('input');
	OBJS['temp'].type = 'button';
	OBJS['temp'].ref = VARS.areaEnum;
	OBJS['temp'].value = VARS.title;
	OBJS['temp'].onclick = function onclick(){
		wkspc_switch(this.ref)
	 }
	OBJS.dspcSwitcher.appendChild(OBJS['temp']);
	wkspc_switch(VARS.areaEnum);
	VARS.areaEnum++;
}

// funzione che commuta le varie aree dilavoro
function wkspc_switch(current){
	for(x=0;x<VARS.areaEnum;x++){
		designAreas[x].vspace.style.visibility='hidden';
	}
	designAreas[current].vspace.style.visibility='visible';
	currArea = designAreas[current];
}

// gestisce gli effetti sui righelli e sul puntatore
function wkspc_pointerEffect(e){
return false;
	OBJS.usrpoint = document.getElementById('MainPaneB');
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	OBJS.dummy.value = e.clientX;
	// riallinea le barrette sui righelli che rincorrono il puntatore
	OBJS.hpoint.style.left = e.clientX-OBJS.usrpoint.offsetLeft-19+'px';
	OBJS.vpoint.style.top = e.clientY-53+'px';
}

// funzioni per la manipolazione del tipo di documento e l'inserimento o eliminazione dei webget contenuti
// ------------------------------------------------------------------------------------------------------------------------------------------------------
//
//
// aggiunge alla posizione indicata un nuovo webget ritorna il numero assegnato
// (l'oggetto è costituito da un involucro 'box' e da uno spazio per inserire elementi figlio 'core')
function docmt_addWebget(type,defaults,vtree){
	// ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root
	this.tree = vtree.split('/');
 	this.tree = this.tree.join('.en');

	// ottiene il numero degli oggetti contenuti per decidere quale numero assegnare al nuovo oggetto
	this.current = currArea.eval(this.tree);
	this.enum = 0;
	for(var sub in this.current){
		if(sub.slice(0,2)=='en'){
			this.enum ++;
		}
	};

	// usa il codice di creazione della libreria specificate per ottenere l'oggetto da appendere
	// gli invia le informazioni sulla posizione attuale 
	this.webget = libs.eval(type).create(vtree+'/'+this.enum,defaults);

	// se il valore ritornato dalla fnzione di creazione è -1 valuta l'errore inviando un messaggio
	// e restituisce -1 ad un eventuale funzione chamante	
	if(this.webget==-1){alert('Non è stato possibile aggiungere il webget alla posizione indicata!');return -1;}
	
	// appende all'albero l'oggetto ottenuto
	this.current['en'+this.enum] = this.webget;
	this.current.core.appendChild(this.current['en'+this.enum].box);
	
	// ritorna il numero assegnato al webget corrente
	return this.enum;
}


// funzioni che selezionano,spostano, ridimensionano i webget 
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

	// vi inserisce il box per il controllo delle dimensioni solo se questo elemento è trascinabile
	if(mode!='nodrag'){currItem.core.insertBefore(OBJS.floater,currItem.core.firstChild)};
	
	// imposta una funzione ritardante che evita la selezione rimbalzata verso gli oggetti inferiori
	VARS.wbgsel = 1;
	window.setTimeout('VARS.wbgsel=0',100);	
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
	itemX = currItem.properties.GEOMETRY[0].slice(0,currItem.properties.GEOMETRY[0].length-2);
	itemY = currItem.properties.GEOMETRY[1].slice(0,currItem.properties.GEOMETRY[1].length-2);
	
	// sottrae al numero con virgola ottenuto l'intero e moltiplica il rimanente per il passo di griglia
	offsetX = (itemX/CONF.gridStep-Math.floor(itemX/CONF.gridStep))*CONF.gridStep;
	offsetY = (itemY/CONF.gridStep-Math.floor(itemY/CONF.gridStep))*CONF.gridStep;
	
	currX = e.clientX;
	currY = e.clientY;
	VARS.dragON = true;
	
//	OBJS.designspace.onmousemove = function onMouseMove(event){wbget_dragWbget(event);OBJS.dummy.value=event.clientX;}
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

// funzioni per ridimensionare il webget
function wbget_reszStart(dirctn,e){
	VARS.dirctn = dirctn;
	// modifica l'evento in base al browser
	if(!e) e = window.event;

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

function wbget_reszWbget(e){
	// modifica l'evento in base al browser
	if(!e) e = window.event;

	// ottiene il valore di differenza della posizione del puntatore rispetto a quella iniziale
	VARS.x= e.clientX-VARS.currX;
	VARS.y= e.clientY-VARS.currY;

	if(CONF.gridSnap==true){
		VARS.x = Math.floor((e.clientX-VARS.currX)/CONF.gridStep)*CONF.gridStep-offsetX;
		VARS.y = Math.floor((e.clientY-VARS.currY)/CONF.gridStep)*CONF.gridStep-offsetY;
	}
	OBJS.dummy.value = VARS.x;
	switch(VARS.dirctn){
		case 'SE' :
			if(( VARS.cig2-0-VARS.x)>10){
				currItem.box.style.left = VARS.cig0-0+VARS.x+'px';
				currItem.box.style.width = VARS.cig2-0-VARS.x+'px';
			}
			if((VARS.cig3-0+VARS.y)>10){
				currItem.box.style.height = VARS.cig3-0+VARS.y+'px';
			}
		break;	

		case 'SW' :
			VARS.cig2-0+VARS.x > 10 ? currItem.box.style.width = VARS.cig2-0+VARS.x+'px' : null;
			VARS.cig3-0+VARS.y > 10 ? currItem.box.style.height = VARS.cig3-0+VARS.y+'px' : null;
		break;
		case 'NE' :
			if(( VARS.cig3-0-VARS.y)>10){
				currItem.box.style.top = VARS.cig1-0+VARS.y+'px'
				currItem.box.style.height = VARS.cig3-0-VARS.y+'px';
			}
			if(VARS.cig2-0-VARS.x > 10){
				currItem.box.style.width = VARS.cig2-0-VARS.x+'px';
				currItem.box.style.left = VARS.cig0-0+VARS.x+'px';
			}
		break;
		case 'NW' :
			if(VARS.cig3-0-VARS.y>10){
				currItem.box.style.top = VARS.cig1-0+VARS.y+'px';
				currItem.box.style.height = VARS.cig3-0-VARS.y+'px';
			}
			VARS.cig2-0+VARS.x > 10 ? currItem.box.style.width = VARS.cig2-0+VARS.x+'px' : null;
			
		break;
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




