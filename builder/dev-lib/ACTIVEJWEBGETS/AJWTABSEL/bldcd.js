{
/* AJWTABSEL */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AJWTABSEL',TARGET:'ALL',FAMILY:'ACTIVEJWEBGETS',ACCEPT:'AJWTABPAGE',CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'300px',
			'GEOMETRY[3]':'200px',
			'STYLE':'',
			'SCHSTL':'',
			'LBLSTL':'',
			'LBLHGT':'20px'
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};
		
		this.box=dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText=";position:absolute;overflow:visible;border:1px solid lightgrey;"+
			'left:'+prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+
			';width:'+prpty.GEOMETRY[2]+';height:'+prpty.GEOMETRY[3]+';';
			
		this.dum=dcr('div');
		dum.style.cssText='position:absolute;width:100%;height:100%;';
		box.appendChild(dum);

		/* alcune proprietà speciali */
		this.table='';this.tbody='';this.tr='';this.td='';this.butt='';this.div='';

		/* crea la tabella principale suddivisa in 2 righe*/
		table=dcr('table');box.appendChild(table);
		table.border='0';table.cellPadding='0';table.cellSpacing='0';
		table.style.cssText=prpty.STYLE+';position:absolute;width:100%;height:100%;';
		tbody=dcr('tbody');table.appendChild(tbody);
		tr=dcr('tr');tbody.appendChild(tr);
		td=dcr('td');tr.appendChild(td);td.height='1';
		box.TCell=dcr('div');td.appendChild(box.TCell);
	/*	box.TCell.style.borderBottom='1px solid lightgrey;'; */
		tr=dcr('tr');tbody.appendChild(tr);
		td=dcr('td');tr.appendChild(td);td.height='100%';td.vAlign='top';
		box.BCell=dcr('div');td.appendChild(box.BCell);
		box.BCell.vAlign='top';
		box.BCell.style.cssText=prpty.SCHSTL+';position:relative;width:100%;height:100%;';

		/* Crea la testata contenente tutte le celle occorrenti per le varie possibilità di formattazione */
		table=dcr('table');box.TCell.appendChild(table);
		table.border='0';table.cellPadding='0';table.cellSpacing='0';
		table.style.cssText='width:100%;height:100%';
		tbody=dcr('tbody');table.appendChild(tbody);
		tr=dcr('tr');tbody.appendChild(tr);
		box.TCell.C0=dcr('td');tr.appendChild(box.TCell.C0);
		box.TCell.C1=dcr('td');tr.appendChild(box.TCell.C1);
		box.TCell.C1.style.width='100%';
		box.TCell.C1.align=(prpty.LBLPOS ? prpty.LBLPOS : 'left');
		/* crea il contenitore dei pulsanti di intestazione */
		this.div=dcr('div');box.TCell.C1.appendChild(div);
		div.style.cssText='border-bottom:1px solid lightgrey;'+prpty.LBLSTL+';height:'+prpty.LBLHGT;
		div.mybox=box;
			
		
/*		box.TCell.C2=dcr('td');tr.appendChild(box.TCell.C2);*/
		box.TCell.C3=dcr('td');tr.appendChild(box.TCell.C3);
		
		box.selectTab=function selectTab(idx){
			with(this.TCell.C1.firstChild){
				/* se è richiesto l'ultimo pulsante posiziona il puntatore opportunamente */
				if(idx=='last')idx=childNodes.length-1;
				/* schiarisce tutti i pulsanti e scurisce quello selezionato */
				for(sub=0;sub<childNodes.length;sub++){childNodes[sub].style.backgroundColor='#E5E5E5';}
				if(idx<childNodes.length){childNodes[idx].style.backgroundColor='lightgrey';}
				else {alert('La scheda selezionata non esiste!');return;};
			}
			/* nascone tutte le schede e poi visualizza quella selezionata */
			with(this.BCell){
				for(sub=0;sub<childNodes.length;sub++){childNodes[sub].style.visibility='hidden';}
				childNodes[idx].style.visibility='visible';
			}					
			this.selectedTab=idx;
		};			
		
		box.buildTabs=function buildTabs(){
			/* svuota lo spazio riservato ai pulsanti per la commutazione */
			this.TCell.C1.firstChild.innerHTML='';
			/* cattura il nodo che contiene questo webget */
			/* (meglio dinamicamente così se questo box viene spostato non ci sono problemi) */
			this.cont=wbt.getOne(this.vtree);
			/* azzera il contatore schede */
			this.temp=0;
			/* analizza gli elementi figlio del nodo del nodo */
			for(var sub in this.cont){
				/* se uno dei figli è un altro nodo-webget lo analizza */ 
				/* (non dovrebbe servire, ma prevedere il riconoscimento del tipo di webget potrebbe prevenire errori) */
				if(sub.slice(0,2)=='en'){
					/* se l'enumeratore eiste ma è vuoto quindi cancellato, lo ignora */
					if(this.cont[sub]!=undefined){
						/* crea il pulsante collegato alla scheda*/
						butt=dcr('input');butt.type='button';this.TCell.C1.firstChild.appendChild(butt);
						this.cont[sub].param.LABEL=(this.cont[sub].param.LABEL ? this.cont[sub].param.LABEL: 'Pagina '+this.temp);
						butt.value=this.cont[sub].param.LABEL;
						butt.style.cssText="border-width:1px;border-bottom:0px;background-color:#E5E5E5;height:100%;font:12px arial,sans;";
						/* crea il riferimento al box del webget contenitore */
						butt.mybox=this.TCell.C1.firstChild.mybox;
						/* memorizza il numero di scheda attuale */
						butt.num=this.temp;
						/* crea il riferimento a questo pulsante nella scheda collegata */
						this.BCell.childNodes[this.temp].mybutt = butt;
						/* funzione che lancia la selezione della scheda associata a questo pulsante quando viene premuto */				
						butt.onclick=function onclick(){this.mybox.selectTab(this.num)};
						/* incrementa il contatore schede */
						this.temp++;
					}
				}
			}
		};

		return {box:box,core:box.BCell,dum:dum,param:prpty};	
	}};
	
	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' : box.style.top = PVal;break;				
				case 'GEOMETRY[2]' : box.style.width = PVal;break;				
				case 'GEOMETRY[3]' : box.style.height = PVal;break;
				case 'STYLE' : box.childNodes[1].style.cssText = PVal+'position:absolute;width:100%;height:100%;';break;
				case 'SCHSTL' : box.BCell.style.cssText = PVal+'position:relative;width:100%;height:100%;';break;
				case 'LBLPOS' : box.TCell.C1.align=PVal;break;
				case 'LBLHGT' : box.TCell.C1.firstChild.style.height=PVal;break;
				case 'LBLSTL' : box.TCell.C1.firstChild.style.cssText='border-bottom:1px solid lightgrey;'+PVal+box.TCell.C1.firstChild.style.height;break;
			}
		}

		/*se non è ugià uscito per valori particolari modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 
		
	}
};