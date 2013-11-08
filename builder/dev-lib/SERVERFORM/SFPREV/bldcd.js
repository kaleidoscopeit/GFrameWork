{
/* SFPREV */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'SFPREV',TARGET:'ALL',FAMILY:'SERVERFORM',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,pts){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'70px',
			'GEOMETRY[3]':'24px',
			'CAPTION':'Button',
			'STYLE':''
			};

		pts.GEOMETRY=pts.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('pts.'+i))eval('pts.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box.style.cssText="position:absolute;"+
		'left:'+pts.GEOMETRY[0]+
		';top:'+pts.GEOMETRY[1]+
		';width:'+pts.GEOMETRY[2]+
		';height:'+pts.GEOMETRY[3]+';';
		this.btn=dcr('input');box.appendChild(btn);
		btn.type='button';
		btn.value=pts.CAPTION;
		btn.className=pts.CSS;
		btn.style.cssText='padding:0px;-moz-box-sizing: border-box;'+pts.STYLE+';width:100%;height:100%;';						
		
		
		return {box:box,dum:box,bt:btn,param:pts};	
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