{
/* VLAYROW */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'VLAYROW',TARGET:'VLAYOUT',FAMILY:'COMMON',ACCEPT:'ALL',CANDRAG:false};
		
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'HEIGHT':'1%',
			'STYLE':'',
			};

		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
		
		this.box = dcr('tr');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.height='1%';

		this.td=dcr('td');box.appendChild(td);
		td.style.height=prpty.HEIGHT;

		this.core = dcr('div');td.appendChild(core);
		core.style.cssText='border-bottom:1px solid lightgrey;position:relative;height:100%;overflow:hidden;'+prpty.STYLE;

		return {box:box,core:core,td:td,dum:core,param:prpty};
	}};
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'HEIGHT':td.style.height=PVal;break;
				case 'STYLE':core.style.cssText='border-bottom:1px solid lightgrey;position:relative;height:100%;overflow:hidden;'+PVal;break;					
			}
		}
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+'=PVal'); 
	}
};