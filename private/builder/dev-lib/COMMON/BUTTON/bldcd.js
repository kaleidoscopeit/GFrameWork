{
/* BUTTON */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'BUTTON',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:false,CANDRAG:true};
	
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'80px',
			'GEOMETRY[3]':'24px',
			'CAPTION':'Button',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText="position:absolute;"+
		'left:'+prpty.GEOMETRY[0]+
		';top:'+prpty.GEOMETRY[1]+
		';width:'+prpty.GEOMETRY[2]+
		';height:'+prpty.GEOMETRY[3]+';';
		this.button=dcr('input');box.appendChild(button);
		button.type='button';
		button.value=prpty.CAPTION;
		button.className=prpty.CSS;
		button.style.cssText='-moz-box-sizing: border-box;'+prpty.STYLE+';width:100%;height:100%;';						
		
		
		return {box:box,dum:box,bt:button,param:prpty};	
	}};

	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(wb,pn,pv){

		/*modifica il valore memorizzato della proprietà corrente*/
		eval('wb.param.'+pn+'=pv;');

		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(wb){
			switch(pn){
				case 'GEOMETRY[0]':box.style.left=pv;break;				
				case 'GEOMETRY[1]':box.style.top=pv;break;				
				case 'GEOMETRY[2]':box.style.width=pv;break;				
				case 'GEOMETRY[3]':box.style.height=pv;break;
				case 'CAPTION':bt.value=pv;break;
				case 'STYLE':bt.style.cssText=(pv!=''?pv:'')+'width:100%;height:100%;';break;
				case 'CSS':bt.className=pv;break;
			}
		}
	}
};