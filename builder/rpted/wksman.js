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
	OBJS.area.style.cssText =   "position:absolute;left:0px;top:0px;border-right:1px solid blue;border-bottom:1px solid;";
	// se si sta trascinando un nuovo webget sopra a questo, lancia l'inserimento del nuovo webget
	OBJS.designspace.onmouseup = function onmouseup(event){
		if(VARS.dragON==true)wbget_dragStop();
		if(VARS.reszON==true)wbget_reszStop(event);
		if(VARS.placeWait==1 && VARS.placeType=='SECTION' && !this.firstChild.firstChild){
			docmt_addWebget(VARS.placeType,Array(event.clientX-VARS.wkspLeft-19,event.clientY-VARS.wkspTop-19),'root');
		}
		// resetta l'azione di posizionamento pendente, se presente
		VARS.placeWait=0;VARS.placeType=undefined;return false;
	};
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
	VARS.areaEnum++;
}

// funzione che commuta le varie aree dilavoro
function wkspc_switch(current){
	for(x=0;x<VARS.areaEnum;x++){
		designAreas[x].vspace.style.visibility='hidden';
	}
	designAreas[current].vspace.style.visibility='visible';
	currArea = designAreas[current];
 	docmt_updateTree();
 	OBJS.floater.style.visibility='hidden';
}

// gestisce gli effetti sui righelli e sul puntatore
function wkspc_pointerEffect(e){
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	OBJS.dummy.value = e.clientX;
	// riallinea le barrette sui righelli che rincorrono il puntatore
	OBJS.hpoint.style.left = e.clientX-VARS.wkspLeft-19+'px';
	OBJS.vpoint.style.top = e.clientY-53+'px';
}




