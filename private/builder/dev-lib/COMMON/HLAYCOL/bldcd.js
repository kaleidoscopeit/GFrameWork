{
/* HLAYCOL */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'HLAYCOL',TARGET:'HLAYOUT',FAMILY:'COMMON',ACCEPT:'ALL',CANDRAG:false};
		
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		if(prpty.WIDTH){this.cellwidth = (prpty.WIDTH.slice(-1) == '%' ? 'width:100%' : 'width:'+prpty.WIDTH);} 
		else {this.cellwidth='';prpty.WIDTH='';};

		this.box = dcr('td');
		box['vtree'] = vtree;
		box.TYPE=TAGSPCS.TYPE;
		box.FAMILY=TAGSPCS.FAMILY;
		box.style.cssText = (prpty.WIDTH ? 'width:'+prpty.WIDTH : '' )+";height:100%;";

		this.dum = dcr('div');box.appendChild(dum);
		dum.style.cssText = "-moz-box-sizing:border-box;border-left:1px solid lightgrey;overflow:visible;position:relative;"+cellwidth+";height:100%;";

		this.core = dcr('div');dum.appendChild(core);
		core.style.cssText = "overflow:hidden;position:relative;"+cellwidth+";height:100%;";
			
		return {box:box,core:core,dum:dum,param:prpty};	
	}};
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
		
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]':case 'GEOMETRY[1]':case 'GEOMETRY[2]':case 'GEOMETRY[3]':break;
				case 'WIDTH' : 
					core.style.width = (PVal.slice(-1) == '%' ? '100%;' : PVal);
					dum.style.width = (PVal.slice(-1) == '%' ? '100%;' : PVal); 
					box.style.width = PVal;
				break;
				case 'STYLE' : dum.style.cssText = "overflow:hidden;position:relative;width:"+core.style.width+";height:100%;"+PVal;break;					
			}
		}
	}
};