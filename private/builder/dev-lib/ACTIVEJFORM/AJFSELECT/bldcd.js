{
/* AJFSELECT */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AJFSELECT',TARGET:'ALL',FAMILY:'ACTIVEJFORM',ACCEPT:false,CANDRAG:true};
	
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
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText='position:absolute;left:'+
			prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+';';
			
		this.select = dcr('select');box.appendChild(select);
		select.style.cssText=prpty.STYLE+';width:'+prpty.GEOMETRY[2]+';height:'+prpty.GEOMETRY[3]+';';
		select.className = prpty.CSS;
					
		this.dum = dcr('div');box.appendChild(dum);
		dum.style.cssText = "position:absolute;left:0px;top:0px;width:100%;height:100%;";

		return {box:box,dum:dum,select:select,param:prpty};		
	}};
	
	/*	aggiorna le proprietà del webget e la visualizzazione */
	this.updateProperty=function(CWbg,PName,PVal){
		/*	modifica il valore memorizzato della proprietà corrente */
		eval('CWbg.param.'+PName+' = PVal;'); 
		/*	esegue un azione differente in base a quale proprietà è stata cambiata */
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' : box.style.top = PVal;break;				
				case 'GEOMETRY[2]' : select.style.width = PVal;break;				
				case 'GEOMETRY[3]' : select.style.height = PVal;break;
				case 'STYLE' : select.style.cssText = ';padding:0px;-moz-box-sizing: border-box;'+PVal+			
					';width:'+param.GEOMETRY[2]+';height:'+param.GEOMETRY[3]+';';
				break;
				case 'CSS' : select.className = PVal; break;
			}
		}
	}
};