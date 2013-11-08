{
/* AJFTEXT */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AJFTEXT',TARGET:'ALL',FAMILY:'ACTIVEJFORM',ACCEPT:false,CANDRAG:true};
	
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'50px',
			'GEOMETRY[3]':'15px',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box['vtree']=vtree;
		box.TYPE=TAGSPCS.TYPE;
		box.style.cssText='position:absolute;'+
		'left:'+prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+';width:'+
		prpty.GEOMETRY[2]+';height:'+prpty.GEOMETRY[3]+';';
		
		this.input=dcr('input');box.appendChild(input);
		input.className=prpty.CSS;
		input.style.cssText='margin:0px;padding:0px;-moz-box-sizing:border-box;'+prpty.STYLE+
		';width:100%;height:100%;';
		
		this.dum=dcr('div');box.appendChild(dum);
		dum.style.cssText="position:absolute;top:0px;left:0px;width:100%;height:100%;";
		
		return {box:box,dum:dum,input:input,param:prpty};				
	}};
	
	
	
	/*	aggiorna le proprietà del webget e la visualizzazione */
	this.updateProperty=function(wb,pn,pv){
		/*	modifica il valore memorizzato della proprietà corrente */
		eval('wb.param.'+pn+'=pv;'); 
		/*	esegue un azione differente in base a quale proprietà è stata cambiata */
		with(wb){switch(pn){
				case 'GEOMETRY[0]':box.style.left=pv;break;				
				case 'GEOMETRY[1]':box.style.top=pv;break;				
				case 'GEOMETRY[2]':input.style.width=pv;break;				
				case 'GEOMETRY[3]':input.style.height=pv;break;
				case 'STYLE':input.style.cssText='margin:0px;padding:0px;-moz-box-sizing:border-box;'+pv+			
					';width:'+param.GEOMETRY[2]+';height:'+param.GEOMETRY[3]+';';
				break;
				case 'CSS':input.className=pv;break;
		}}
	}
};