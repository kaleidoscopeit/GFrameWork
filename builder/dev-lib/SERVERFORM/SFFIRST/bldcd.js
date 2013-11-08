{
/* SFPREV */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'SFFIRST',TARGET:'ALL',FAMILY:'SERVERFORM',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'70px',
			'GEOMETRY[3]':'24px',
			'CAPTION':'Button',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box = dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box['vtree'] = vtree;
		box.style.cssText = 	"position:absolute;"+
		'left:'+prpty.GEOMETRY[0]+
		';top:'+prpty.GEOMETRY[1]+
		';width:'+prpty.GEOMETRY[2]+
		';height:'+prpty.GEOMETRY[3]+';';
		this.button = dcr('input');box.appendChild(button);
		button.type = 'button';
		button.value = prpty.CAPTION
		button.className = prpty.CSS;
		button.style.cssText='padding:0px;-moz-box-sizing: border-box;'+prpty.STYLE+';width:100%;height:100%;';						
		
		
		return {box:box,dum:box,bt:button,param:prpty};	
	}};

	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){

		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;');

		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : 	box.style.left = PVal;break;				
				case 'GEOMETRY[1]' :	box.style.top = PVal;break;				
				case 'GEOMETRY[2]' :	box.style.width = PVal;break;				
				case 'GEOMETRY[3]' :	box.style.height = PVal;break;
				case 'CAPTION' : bt.value = PVal; break;
				case 'STYLE' : bt.style.cssText=( PVal!='' ?  PVal : '')+'width:100%;height:100%;'; break;
				case 'CSS' : bt.className = PVal; break;
			}
		}
	}
};