{
/* SFORM */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'SFORM',TARGET:'ALL',FAMILY:'SERVERFORM',ACCEPT:'ALL',CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'100%',
			'GEOMETRY[3]':'100%',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box = dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;

		box['vtree'] = vtree;
		box.style.cssText = 'position:absolute;background-color:lightyellow;'+
			'left:'+prpty.GEOMETRY[0]+
			';top:'+prpty.GEOMETRY[1]+
			';width:'+prpty.GEOMETRY[2]+
			';height:'+prpty.GEOMETRY[3]+
			';';

		this.face=dcr('div');box.appendChild(face);
		face.style.cssText = 'position:absolute;margin:-1px;border:1px solid black;background-color:yellow;'+
			'left:0px;top:0px;width:20px;height:20px;';
		this.face.innerHTML = 'F';
			
		this.core = dcr('div');box.appendChild(core);
		core.style.cssText = prpty.STYLE+';position:absolute;top:0px;width:100%;height:100%;';
		
		return {box:box,core:core,dum:box,param:prpty};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'STYLE' : core.style.cssText += PVal+';position:absolute;width:100%;height:100%;'; break;
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' :	box.style.top = PVal;break;				
				case 'GEOMETRY[2]' :	box.style.width = PVal;break;				
				case 'GEOMETRY[3]' :	box.style.height = PVal;break;
				
			}
		}
		/* se non è uscito dalla funzione per props particolari modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 

	}
};