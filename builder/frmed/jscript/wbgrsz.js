/*
	Funzioni che estendono l'oggetto principale per la manipolazione dei 
	Webgets aggiungendo le funzioni per modificare la dimensione dei Webgets.
	
	wbt		:	Oggetto principale per la manipolazione dei Webgets
	rst		:	Inizia il ridimensionamento del webget.
					  
*/
/* Inizia il ridimensionamento del webget. Deve essre chiamata dai cubetti ai lati 
	del webget e collegati all'area di selezione. Deve essere specificata la direzione del
	ridimensionamento corrispondente all'angolo dove è stata iniziato il ridimensionamento.
	Da ora in poi ogni movimento del mouse provocherà il ridimensionamento del webget. 
	Tutto si fermerà quando il tasto del mouse verrà rilasciato. */
wbt.rst=function(dirctn,e){
	/* inibisce la funzione di selezione */
/*	this.cantsel=true;*/
	/* inibisce la funzione di trascinamento */
	this.cantdrag=true;

	this.dirctn=dirctn;
	/* modifica l'evento in base al browser */
	if(!e)e=window.event;

	/* abilita il trascinamento del wbt selezionato */
	document.onmousemove=function onmousemove(event){wbt.resize(event);};
	document.onmouseup=function onmouseup(event){wbt.reszStop();};

	with(this){
		/* se le dimensioni del wbt sono percentuali, vengono automaticamente convertite in scalari */
		if(cwb.box.style.width.indexOf('%')>0){
			cwb.box.style.width=cwb.box.offsetWidth+'px';
			cwb.param.GEOMETRY[2]=cwb.box.style.width;
		}
		if(cwb.box.style.height.indexOf('%')>0){
			cwb.box.style.height=cwb.box.offsetHeight+'px';
			cwb.param.GEOMETRY[3]=cwb.box.style.height;
		}
	
		this.currX=e.clientX;
		this.currY=e.clientY;

		/* misura compensativa: se non si trovano i parametri sulla larghezza li recupera dal box */
		this.cwb.param.GEOMETRY[2]?'':this.cwb.param.GEOMETRY[2]=this.cwb.box.offsetWidth+'px';
		this.cwb.param.GEOMETRY[3]?'':this.cwb.param.GEOMETRY[3]=this.cwb.box.offsetHeight+'px';	

		/* congela le dimensioni iniziali del webget */
		this.cigL=this.cwb.param.GEOMETRY[0].slice(0,this.cwb.param.GEOMETRY[0].length-2)-0;
		this.cigT=this.cwb.param.GEOMETRY[1].slice(0,this.cwb.param.GEOMETRY[1].length-2)-0;
		this.cigW=this.cwb.param.GEOMETRY[2].slice(0,this.cwb.param.GEOMETRY[2].length-2)-0;
		this.cigH=this.cwb.param.GEOMETRY[3].slice(0,this.cwb.param.GEOMETRY[3].length-2)-0;
		
		/* blocca l'accidentale riselezione di un altro wbt */
		this.wbgsel = 1;
	}
};

wbt.resize=function(e){
	/* modifica l'evento in base al browser */
	if(!e) e = window.event;
	
	with(this){
		/* ottiene il valore di differenza della posizione del puntatore rispetto a quella iniziale */
		this.x=e.clientX-currX;
		this.y=e.clientY-currY;

		if(CONF.gridSnap==true){
			this.x=Math.floor((e.clientX-currX)/CONF.gridStep)*CONF.gridStep-offsetX;
			this.y=Math.floor((e.clientY-currY)/CONF.gridStep)*CONF.gridStep-offsetY;
		}
		
		/*edt.dummy.value = x;*/

		switch(dirctn){
			case 'SE' :
				this.cigNW=(cigW-x>10 ? cigW-x : 10);
				this.cigNH=(cigH+y>10 ? cigH+y : 10);
				this.cigNL=(cigW-x>10 ? cigL+x : cigL+cigW-10);
				this.cigNT=cigT;
			break;	
	
			case 'SW' :
				this.cigNW=(cigW+x>10 ? cigW+x : 10);
				this.cigNH=(cigH+y>10 ? cigH+y : 10);
				this.cigNL=cigL;this.cigNT=cigT;				
			break;
			
			case 'NE' :
				this.cigNW=(cigW-x>10 ? cigW-x : 10);
				this.cigNH=(cigH-y>10 ? cigH-y : 10);
				this.cigNL=(cigW-x>10 ? cigL+x : cigL+cigW-10);
				this.cigNT=(cigH-y>10 ? cigT+y : cigT+cigH-10);	
			break;
			
			case 'NW' :
				this.cigNW=(cigW+x>10 ? cigW+x : 10);
				this.cigNH=(cigH-y>10 ? cigH-y : 10);
				this.cigNL=cigL;
				this.cigNT=(cigH-y>10 ? cigT+y : cigT+cigH-10);	
			break;
		}

/*		float.move(0,this.glbOffsetLeft+cigNL,this.cigNT+this.glbOffsetTop); */
/*		float.resize(0,cigNW,cigNH); */
	
		this.upy('GEOMETRY[0]',cigNL+'px');
		this.upy('GEOMETRY[1]',cigNT+'px');
		this.upy('GEOMETRY[2]',cigNW+'px');
		this.upy('GEOMETRY[3]',cigNH+'px');
	}
};

wbt.reszStop=function(){
	/* annulla gli eventi di spostamento */
	document.onmousemove='';
	/* riabilita la possibilità di selezionare un altro wbt e la possibilità di trascinare */
	this.cantsel=false;this.cantdrag=false;
	/* aggiorna il box delle proprietà */
	this.upb();
};