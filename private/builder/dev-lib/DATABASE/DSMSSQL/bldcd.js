{
/* DSMSSQL */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'DSMSSQL',TARGET:'ALL',FAMILY:'DATABASE',SPEC:true};
	
	this.create=function(vtree,prpty){
		this.box = dcr('div');
		this.box.TYPE=this.TAGSPCS.TYPE;
		this.box['vtree'] = vtree;
		return {box:this.box,param:prpty};	
	};
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
	};
}