{
/* PROGRBAR */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'PROGRBAR',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'100px',
			'GEOMETRY[3]':'25px',
			'PROGRESS':'50',
			'LABELTOP':'0px',
			'STYLE':'',
			'LABELSTYLE':'',
			'BARSTYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
		
		this.box=dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText='border:1px solid lightgrey;'+prpty.STYLE+
			';-moz-box-sizing:border-box;position:absolute;'+			
			'left:'+prpty.GEOMETRY[0]+
			';top:'+prpty.GEOMETRY[1]+
			';width:'+prpty.GEOMETRY[2]+
			';height:'+prpty.GEOMETRY[3]+';';
			
		this.core=dcr('div');box.appendChild(core);
		core.style.cssText='background-color:lightgreen;'+prpty.BARSTYLE+
		';position:absolute;width:'+prpty.PROGRESS+'%;height:100%;';

		this.label=dcr('div');box.appendChild(label);
		label.style.cssText = 'text-align:center;'+prpty.LABELSTYLE+';top:'+prpty.LABELTOP+';position:absolute;width:100%;height:100%;';
		label.innerHTML=prpty.PROGRESS+'%';
		
		return {box:box,core:core,dum:box,label:label,param:prpty};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'STYLE' : box.style.cssText = 'border:1px solid lightgrey;'+PVal+
					';-moz-box-sizing:border-box;position:absolute;left:'+box.style.left+
					';top:'+box.style.top+
					';width:'+box.style.width+
					';height:'+box.style.height+';'; 
				break;
				case 'BARSTYLE' : core.style.cssText = PVal+
					';position:absolute;width:'+core.style.width+
					';height:'+core.style.height+';'; 
				break;
				case 'LABELTOP' : label.style.top=PVal;break;
				case 'LABELSTYLE' : label.style.cssText='text-align:center;'+PVal+'top:'+label.style.top+';position:absolute;width:100%;height:100%;';break;
				case 'PROGRESS' : core.style.width=PVal+'%';label.innerHTML=PVal+'%';break;
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' :	box.style.top = PVal;break;				
				case 'GEOMETRY[2]' :	box.style.width = PVal;break;				
				case 'GEOMETRY[3]' :	box.style.height = PVal;break;
				break; 
				
			}
		}
		/* se non è uscito dalla funzione per props particolari modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
	}
};