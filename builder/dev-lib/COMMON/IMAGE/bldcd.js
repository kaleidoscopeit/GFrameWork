{
/* IMAGE */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'IMAGE',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'SRC':'',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
			
		this.box=dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText="position:absolute;"+'left:'+prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1];

		this.img=dcr('img');box.appendChild(img);
		img.style.cssText='left:0px;top:0px;'+
			(prpty.GEOMETRY[2]?'width:'+prpty.GEOMETRY[2]+';':'')+
			(prpty.GEOMETRY[3]?'height:'+prpty.GEOMETRY[3]+';':'');
		img.box=box;
		img.src=(prpty.SRC.substr(0,7)=='http://'?prpty.SRC:'../../<?php echo $SG['bld']['root']?>/'+prpty.SRC);
		/* Quando l'immagine è stata caricata regola la dimensione in base alla presenza di
			dimensioni orrizzontale e/o verticale specificate */
		img.onload=function(){
			if(this.naturalWidth-0<1)this.onerror();
			/*img.style.width=(prpty.GEOMETRY[2]?prpty.GEOMETRY[2]:'');
			img.style.height=(prpty.GEOMETRY[3]?prpty.GEOMETRY[3]:'');*/
		};

		img.onerror=function(){
			this.src='imges/48/image.png';
			prpty.GEOMETRY[2]='';
			prpty.GEOMETRY[3]='';
		};

		this.core=dcr('div');box.appendChild(core);img.core=core;
		core.style.cssText=prpty.STYLE+"position:absolute;left:0px;top:0px;width:100%;height:100%;";
		
		return {box:box,dum:box,core:core,img:img,param:prpty};
	}};
	

	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+'=PVal;'); 
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'STYLE':core.style.cssText=PVal+';position:absolute;left:0px;top:0px;width:100%;height:100%;'; break;
				case 'GEOMETRY[0]':box.style.left=PVal;break;				
				case 'GEOMETRY[1]':box.style.top=PVal;break;				
				case 'GEOMETRY[2]':				   
					img.style.width=PVal;
					break;
				case 'GEOMETRY[3]':
					img.style.height=PVal;
					break;
				case 'SRC':
						img.src=(PVal.substr(0,7)=='http://'?PVal:'../../<?php echo $SG['bld']['root']?>/'+PVal);						
				break;
			}
		}
	}
}