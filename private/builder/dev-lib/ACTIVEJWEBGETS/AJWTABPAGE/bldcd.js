{
/* AJWTABPAGE */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AJWTABPAGE',TARGET:'AJWTABSEL',FAMILY:'ACTIVEJWEBGETS',ACCEPT:'ALL',CANDRAG:false,CANRESIZE:false};

	this.create=function(vtree,prpty){with(this){
		if(!prpty.STYLE)prpty.STYLE='';
		
		this.box = dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box['vtree'] = vtree;
		box.style.cssText = prpty.STYLE+";position:absolute;overflow:hidden;left:0px;top:0px;width:100%;height:100%;";
			
		/* alcune proprietà speciali */
		this.table='';this.tbody='';this.tr='';this.td='';this.butt='';this.div='';

		box.onAppend=function onAppend(){
			this.parent.buildTabs();
			this.parent.selectTab('last');
		};

		box.onRemove=function onRemove(){
			this.parent.buildTabs();
			this.parent.selectTab('last');
		};

		/* elemento fantasma dove si inserisce la griglia di controllo*/
		this.dum = dcr('div');box.appendChild(dum);
		dum.style.cssText='position:absolute;width:100%;height:100%;top:0px;left:0px;';
						
		return {box:box,core:box,dum:dum,param:prpty};	
	}};
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : 
				case 'GEOMETRY[1]' :				
				case 'GEOMETRY[2]' :				
				case 'GEOMETRY[3]' : return;break;
				case 'LABEL' : box.mybutt.value = PVal;break;
				case 'STYLE' : box.style.cssText = PVal+";position:absolute;overflow:visible;left:0px;top:0px;width:100%;height:100%;";break;
			}
		}

		/*se non è ugià uscito per valori particolari modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
	}
};