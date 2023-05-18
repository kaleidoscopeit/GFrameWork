{
/* AREA */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AREA',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:'ALL',CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'100px',
			'GEOMETRY[3]':'100px',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree'] = vtree;
		box.style.cssText = 'position:absolute;border:1px dotted pink;-moz-box-sizing:border-box;'+
			'left:'+prpty.GEOMETRY[0]+
			';top:'+prpty.GEOMETRY[1]+
			';width:'+prpty.GEOMETRY[2]+
			';height:'+prpty.GEOMETRY[3]+';';
			
		this.table = dcr('table');box.appendChild(table);
		table.cellPadding=0;table.cellSpacing=0;
		table.style.cssText='position:absolute;height:100%;width:100%';
		this.tbody = dcr('tbody');table.appendChild(tbody);
		this.tr = dcr('tr');tbody.appendChild(tr);
		this.td = dcr('td');tr.appendChild(td);
		td.height='100%';td.align='center';
		
		this.core = dcr('div');td.appendChild(core);
		core.style.cssText='border:1px dotted pink;'+prpty.STYLE+';position:relative;width:100%;height:100%;';
		if(prpty.CENTW)core.style.width=prpty.CENTW;
		if(prpty.CENTH)core.style.height=prpty.CENTH;


		return {box:box,core:core,dum:box,param:prpty};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(wb,pn,pv){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(wb){
			switch(pn){
				case 'STYLE':core.style.cssText='border:1px dotted pink;'+pv+';position:relative;width:'+core.style.width+';height:'+core.style.height+';';break;
				case 'GEOMETRY[0]':box.style.left=pv;break;				
				case 'GEOMETRY[1]':box.style.top=pv;break;				
				case 'GEOMETRY[2]':box.style.width=pv;break;				
				case 'GEOMETRY[3]':box.style.height=pv;break;
				case 'CENTW':core.style.width=(pv?pv:'100%');break;
				case 'CENTH':core.style.height=(pv?pv:'100%');break;
				case 'CENTON':
/*					 if(PVal==true){
					 	core.style.border='1px dotted pink';
						core.style.width = (CWbg.param.CENTW ? CWbg.param.CENTW : '100%');
						core.style.height = (CWbg.param.CENTH ? CWbg.param.CENTH :'100%');
					} else {
					 	core.style.border='0px';
						core.style.width = '100%';
						core.style.height = '100%';					
					} */
				break; 
				
			}
		}
		/* se non è uscito dalla funzione per props particolari modifica il valore memorizzato della proprietà corrente*/
		eval('wb.param.'+pn+'=pv;'); 
	}
}