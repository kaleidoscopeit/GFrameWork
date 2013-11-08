{
/* FTAREA */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'FTAREA',TARGET:'ALL',FAMILY:'FREETABLE',ACCEPT:'ALL',CANDRAG:false};
	
	this.create=function(vtree,prpty){with(this){
		if(!prpty.STYLE)prpty.STYLE='';
	
		this.core = dcr('div');
		core.style.cssText = "position:absolute;overflow:hidden;width:100%;height:100%;top:0px;left:0px;";
		this.dum = dcr('div');
		dum.style.cssText=prpty.STYLE+';position:absolute;width:100%;height:100%;top:0px;left:0px;';
		this.box = dcr('div');
		box.TYPE = TAGSPCS.TYPE;
		box.vtree = vtree;
		box.style.cssText = "position:relative;left:0px;top:0px;width:100%;height:100%;";


		dum.appendChild(core);
		box.appendChild(dum);
		
		return {box:box,core:core,dum:dum,param:prpty};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
		
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
					case 'STYLE' : core.style.cssText = PVal+';position:absolute;width:100%;height:100%;top:0px;left:0px;'; break;
			}
		}
	}
};