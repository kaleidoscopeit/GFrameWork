{
/* ICON */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'ICON',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'48px',
			'GEOMETRY[3]':'48px',
			'SRC':'',
			'STYLE':'',
			'SIZE':'48'
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
			
		this.box = dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box.style.cssText='position:absolute;'+
			'left:'+prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+';';

		this.img=dcr('img');box.appendChild(img);
		img.style.cssText=prpty.STYLE+';left:0px;top:0px;';
		img.src='../../<?php echo $SG['bld']['root']?>/themes/'+
		(prpty.THEME?prpty.THEME:'default')+
		'/icons/'+prpty.SIZE+'/normal/'+prpty.SRC;
	
		img.onerror=function(){
			this.src='imges/48/image.png';
			prpty.GEOMETRY[2]='';
			prpty.GEOMETRY[3]='';
		};

		this.core=dcr('div');box.appendChild(core);
		core.style.cssText = "position:absolute;left:0px;top:0px;width:100%;height:100%;";
		
		return {box:box,dum:box,core:core,img:img,param:prpty};
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
				case 'SRC':
					if(PVal.length>3){ 
						box.style.width='';
						box.style.height='';
						core.innerHTML='';
						img.src = '../../<?php echo $SG['bld']['root']?>/themes/'+
						(CWbg.param['THEME']?CWbg.param['THEME']:'default')+
						'/icons/'+CWbg.param['SIZE']+'/normal/'+PVal;
					} else {
						img.src = '';						
					}
				break;
			}
		}
	}
};