{
/* SFTEXT */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'SFTEXT',TARGET:'ALL',FAMILY:'SERVERFORM',ACCEPT:false,CANDRAG:true};
	
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'80px',
			'GEOMETRY[3]':'24px',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box['vtree']=vtree;
		box.TYPE=TAGSPCS.TYPE;
		box.style.cssText='position:absolute;left:'+
		prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+';width:'+
		prpty.GEOMETRY[2]+';height:'+prpty.GEOMETRY[3]+';';
		
		this.input=dcr('input');box.appendChild(input);
		input.className=prpty.CSS;
		input.style.cssText='margin:0px;padding:0px;-moz-box-sizing:border-box;'+prpty.STYLE+
		';width:100%;height:100%;';
		
		this.dum=dcr('div');box.appendChild(dum);
		dum.style.cssText="position:absolute;top:0px;left:0px;width:100%;height:100%;";
		
		return {box:box,dum:dum,input:input,param:prpty};				
	}};	
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+'=PVal;');
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]':box.style.left=PVal;break;				
				case 'GEOMETRY[1]':box.style.top=PVal;break;				
				case 'GEOMETRY[2]':input.style.width=PVal;break;				
				case 'GEOMETRY[3]':input.style.height=PVal;break;
				case 'STYLE':input.style.cssText='margin:0px;padding:0px;-moz-box-sizing:border-box;'+PVal+			
					';width:'+param.GEOMETRY[2]+';height:'+param.GEOMETRY[3]+';';
				break;
				case 'CSS':input.className=PVal;break;
				case 'FIELD':
					if(!param.ID){
						wbt.upy('ID',PVal,CWbg.vtree);
						wbt.upb()
					}
				break;
			}
		}
	}
};