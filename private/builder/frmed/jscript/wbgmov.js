/*
	cwb		:	riferimento al wbt corrente

*/
/*funzioni per muovere il wbt */
wbt.dragStart=function(e){
	/* se il wbt selezionato non può essere mosso annulla la funzione */
	if(!libs[this.cwb.box.TYPE].TAGSPCS.CANDRAG)return false;
	/* se il wbt è in ridimensionamento la funzione viene annullata */
	if(this.cantdrag==true){return false;};
	/* inibisce la funzione di selezione */
	this.cantsel=true;
	/* modifica l'aspetto del puntatore */
	this.cwb.box.style.cursor='move';
	/* modifica l'evento in base al browser */
	if(!e)e=window.event;

	/* abilita il trascinamento del wbt selezionato */
	document.onmousemove=function(event){wbt.drag(event);};
	document.onmouseup=function(event){wbt.dragStop()};

	/*	congela la posizione iniziale del wbt rispetto al suo contenitore */
	this.itemX=this.cwb.param.GEOMETRY[0].slice(0,this.cwb.param.GEOMETRY[0].length-2)-0;
	this.itemY=this.cwb.param.GEOMETRY[1].slice(0,this.cwb.param.GEOMETRY[1].length-2)-0;

	/* ottiene lo scarto dalla posizione iniziale del wbt al punto più vicino nella griglia */
	this.offsetX=(this.itemX/CONF.gridStep-Math.floor(this.itemX/CONF.gridStep))*CONF.gridStep;
	this.offsetY=(this.itemY/CONF.gridStep-Math.floor(this.itemY/CONF.gridStep))*CONF.gridStep;

	/* cattura la posizione iniziale del puntatore */	
	this.currX=e.clientX;
	this.currY=e.clientY;
};

wbt.drag=function(e){
	/* modifica l'evento in base al browser */
	if(!e)e=window.event;
	with(this){
	
		/* ottiene lo spostamento in px del puntatore rispetto alla posizioni iniziale */
		this.floatX=e.clientX-currX;
		this.floatY=e.clientY-currY;

		/* effettua gli arrotondamenti in base alla griglia */
		if(CONF.gridSnap==true){
			floatX=Math.floor((e.clientX-currX)/CONF.gridStep)*CONF.gridStep-offsetX;
			floatY=Math.floor((e.clientY-currY)/CONF.gridStep)*CONF.gridStep-offsetY;
		}
		edt.dummy.value=e.clientX;
		/* sposta il wbt */
		this.upy('GEOMETRY[0]',itemX+floatX+'px');
		this.upy('GEOMETRY[1]',itemY+floatY+'px');
	}
};

wbt.dragStop=function(){
	/* annulla gli eventi di spostamento */
	document.onmousemove=null;
	document.onmouseup=null;
	/* riabilita la possibilità di selezionare un altro wbt */
	this.cantsel=false;	
	/* aggiorna il box delle proprietà */
	this.upb();
};
