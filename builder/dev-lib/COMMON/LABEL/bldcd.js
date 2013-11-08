{
/* LABEL */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'LABEL',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:false,CANDRAG:true};
	
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'50px',
			'GEOMETRY[3]':'15px',
			'CAPTION':'Label',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box = dcr('div');
		box.TYPE=this.TAGSPCS.TYPE;
		box.style.cssText =	'position:absolute;'+
									'left:'+prpty.GEOMETRY[0]+';'+
									'top:'+prpty.GEOMETRY[1]+';'+
									'width:'+prpty.GEOMETRY[2]+';'+
									'height:'+prpty.GEOMETRY[3]+';';
			
		box['vtree']=vtree;
		/* div utulizzatgo per disegnare il bordo autonomo rispetto al contenuto */
		/*this.bdr=dcr('div');box.appendChild(bdr);
		bdr.style.cssText='-moz-box-sizing:border-box;border:1px solid lightgrey;width:100%;height:100%;';
*/		
		this.core=dcr('div');box.appendChild(core);
		core.style.cssText='position:absolute;overflow:visible;left:0px;top:0px;width:100%;height:100%;';
		
		this.table=dcr('table');core.appendChild(table);
		table.style.cssText='width:100%;height:100%;';
		table.border=0;table.cellPadding=0;table.cellSpacing=0;

		this.tr = dcr('tr');table.appendChild(tr);
		this.td = dcr('td');tr.appendChild(td);
		td.className=prpty.CSS;
		td.noWrap=prpty.NOWRAP;
		td.style.cssText=prpty.STYLE;
		td.innerHTML = prpty.CAPTION;
		
		return {box:box,dum:core,core:core,lbl:td,param:prpty};
	}};
	
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal'); 

		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' :	box.style.top = PVal;break;				
				case 'GEOMETRY[2]' :	box.style.width = PVal;break;				
				case 'GEOMETRY[3]' :	box.style.height = PVal;break;
				case 'CAPTION' : lbl.innerHTML = PVal; break;
				case 'CSS' : lbl.className=PVal; break;
				/*case 'STYLE' : dum.style.cssText = PVal+';-moz-box-sizing: border-box;overflow:hidden;width:100%;height:100%;'; break;*/
				case 'STYLE' : lbl.style.cssText=PVal; break;
				case 'NOWRAP' : lbl.noWrap = PVal;break;
			}
		}
	}
};