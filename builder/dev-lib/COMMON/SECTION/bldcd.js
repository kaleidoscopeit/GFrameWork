{
/* SECTION */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'SECTION',TARGET:false,FAMILY:'COMMON',ACCEPT:'ALL',CANDRAG:false};
		
	this.create = function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'500px',
			'GEOMETRY[3]':'300px',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
	
		this.box = dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box.FAMILY=this.TAGSPCS.FAMILY;		
		box.style.cssText = ';position:relative;width:'+
			prpty.GEOMETRY[2]+";height:"+prpty.GEOMETRY[3]+
			";background-image: url('imges/grid08.gif');border:1px solid black;";
		box['vtree'] = vtree;

		this.core = dcr('div');
		core.style.cssText = prpty.STYLE+';width:100%;height:100%;';
		box.appendChild(core);
			
		return {box:box,core:core,dum:core,param:prpty};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : case 'GEOMETRY[1]' : return; break; 
				case 'GEOMETRY[2]' : box.style.width = PVal;break;				
				case 'GEOMETRY[3]' : box.style.height = PVal;break;				
				case 'STYLE' : core.style.cssText = PVal+';width:100%;height:100%;'; break;
			}
		}
		
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal'); 
	}
};