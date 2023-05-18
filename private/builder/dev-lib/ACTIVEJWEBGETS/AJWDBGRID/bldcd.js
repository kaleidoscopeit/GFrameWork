{
/* AJWDBGRID */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'AJWDBGRID',TARGET:'ALL',FAMILY:'ACTIVEJWEBGETS',ACCEPT:false,CANDRAG:true};

	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'300px',
			'GEOMETRY[3]':'200px',
			'PROGRESS':'50',
			'LABELTOP':'0px',
			'STYLE':'border:1px solid;',
			'CHSTYLE':'',
			'RHSTYLE':'',
			'FIELDS':'0',
			'CHLABEL':'Campo 0',
			'ROWHEIGHT':'20px',
			'RHWIDTH':'20px',
			'FLDSTYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box=dcr('div');
		box.TYPE=TAGSPCS.TYPE;
		box['vtree']=vtree;
		box.style.cssText=prpty.STYLE+"position:absolute;overflow:hidden;border:1px solid;"+
			'left:'+prpty.GEOMETRY[0]+';top:'+prpty.GEOMETRY[1]+
			';width:'+prpty.GEOMETRY[2]+';height:'+prpty.GEOMETRY[3]+';';
			
		/* alcune proprietà speciali */
		this.table='';this.tbody='';this.tr='';this.td='';this.butt='';this.div='';

		/* crea la tabella suddivisa in 4 parti*/
		table=dcr('table');box.appendChild(table);
		table.border=0;table.cellPadding=0;table.cellSpacing=0;
		table.style.cssText='width:100%;height:100%;';
		tbody=dcr('tbody');table.appendChild(tbody);
		
		tr=dcr('tr');tbody.appendChild(tr);

		/* cella alto SX */
		box.TLCell=dcr('td');tr.appendChild(box.TLCell);

		/* Cella alto DX */
		td=dcr('td');tr.appendChild(td);
		td.noWrap=true;
		box.TRCell=dcr('div');td.appendChild(box.TRCell);
		box.TRCell.style.cssText='width:100%;overflow:hidden;height:20px;';

		tr=dcr('tr');tbody.appendChild(tr);

		/* Cell basso SX */
		td=dcr('td');tr.appendChild(td);
		td.style.height='100%;';td.vAlign='top';
		div=dcr('div');td.appendChild(div);
		div.style.cssText='position:relative;overflow:hidden;width:'+prpty.RHWIDTH+';height:100%;';
		box.BLCell=dcr('div');div.appendChild(box.BLCell);
		box.BLCell.style.cssText='position:absolute;width:100%;';

		/* Cella basso DX */
		td=dcr('td');tr.appendChild(td);
		td.style.cssText='width:100%;height:100%;';td.vAlign='top';
		div=dcr('div');td.appendChild(div);
		div.style.cssText='position:relative;overflow:auto;width:100%;height:100%;';
		box.BRCell=dcr('div');div.appendChild(box.BRCell);
		box.BRCell.style.cssText='position:absolute;';

		/*funzione personale di riempimento tabella, utile par l'aggiornamento dinamico */
		box.fillTable=function(prpty){with(this){
			this.mycol={};this.myhead={};this.myChead={};
			TLCell.innerHTML='';
			TRCell.innerHTML='';
			BLCell.innerHTML='';
			BRCell.innerHTML='';
			/*	cella di incrocio	*/
			this.butt=dcr('button');TLCell.appendChild(butt);myhead[0]=butt;
			TLCell.style.width=prpty.RHWIDTH;TLCell.style.height=prpty.ROWHEIGHT;
			butt.style.cssText='border:1px outset #aaaaaa;background-color:#bbbbbb;height:100%;width:100%;'+prpty.CHSTYLE;
			
			/* intestazioni colonne */
			for(sub=0;sub<prpty.FIELDS.split(',').length+1;sub++){
				/* intestazioni colonne */
				butt=dcr('input');butt.type='button';TRCell.appendChild(butt);myhead[sub+1]=butt;
				butt.value=(prpty.CHLABEL.split(',')[sub] ? prpty.CHLABEL.split(',')[sub] : '');
				butt.style.cssText='text-align:left;padding:0px;font: 10px sans,arial;border:1px outset #aaaaaa;background-color:#bbbbbb;height:20px;'+prpty.CHSTYLE;
				if(sub==prpty.FIELDS.split(',').length)butt.style.width='100%';
			}
			/* intestazioni righe */
			for(sub=0;sub<10;sub++){			
				butt=dcr('input');butt.type='button';BLCell.appendChild(butt);myChead[sub]=butt;
				butt.style.cssText='font: 10px sans,arial;border:0px;border-bottom:1px solid #cccccc;background-color:#eeeeee;'+
					prpty.RHSTYLE+';height:'+prpty.ROWHEIGHT+';width:100%;';
				butt.value=sub;				
			}

			/* CREA L'AREA DEI DATI CON DELLE COLONNE VUOTE CONTROLLATE DALLE INTESTAZIONI */
			table=dcr('table');BRCell.appendChild(table);
			table.style.cssText='position:relative;margin-left:-1px;width:100%;height:100%;';
			table.border='0';table.cellSpacing='0';table.cellPadding='0';
			tbody=dcr('tbody');table.appendChild(tbody);
			tr=dcr('tr');tbody.appendChild(tr);
			/* per ogni colonna da disegnare aggiunge questi elementi */
			for(sub=0;sub<prpty.FIELDS.split(',').length+1;sub++){
				td=dcr('td');tr.appendChild(td);
				td.vAlign='top';
				td.style.width=(sub<prpty.FIELDS.split(',').length ?  '1px;' : '100%;');

				this.div=dcr('div');td.appendChild(div);box.mycol[sub]=div;
				if(sub==prpty.FIELDS.split(',').length)div.style.width='100%';
				div.style.oveflow='hidden';
			
				table=dcr('table');div.appendChild(table);
				table.border='0';table.cellSpacing='0';table.cellPadding='0';
				table.style.cssText='width:100%;';
				tbody=dcr('tbody');table.appendChild(tbody);
			
				/* per ogni riga aggiunge una riga */
				for(rw=0;rw<10;rw++){
					this.ntr=dcr('tr');tbody.appendChild(ntr);
					this.ntd=dcr('td');ntr.appendChild(ntd);
					ntd.innerHTML='&nbsp;';
					ntd.style.cssText='border-left:1px solid grey;padding-left:3px;border-bottom:1px solid lightgrey;font:10px sans,arial;'+prpty.FLDSTYLE+';height:'+prpty.ROWHEIGHT;
				}
			}
			/* sfrutta la funzione di ridimensionamento delle colonne */
			box.colResize(prpty);
		}};

		box.colResize=function(prpty){
			for(sub=0;sub<prpty.FIELDS.split(',').length;sub++){
				this.mycol[sub].style.width=(prpty.CHWIDTH ? (prpty.CHWIDTH.split(',')[sub] ? prpty.CHWIDTH.split(',')[sub] : '200px') : '200px');
				this.myhead[sub+1].style.width=(prpty.CHWIDTH ? (prpty.CHWIDTH.split(',')[sub] ? prpty.CHWIDTH.split(',')[sub] : '200px') : '200px');
			}
		};
		
		/* lancia funzione di riempimento appena creata */
		box.fillTable(prpty);
	
		return {box:box,dum:box,param:prpty};	
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(wb,pn,pv,sa){
		/*modifica il valore memorizzato della proprietà corrente*/
		eval('wb.param.'+pn+'=pv;'); 
		
		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(wb){
			switch(PName){
				case 'STYLE':dum.style.cssText+=pv; break;
				case 'GEOMETRY[0]':box.style.left=pv;break;				
				case 'GEOMETRY[1]':box.style.top=pv;break;				
				case 'GEOMETRY[2]':box.style.width=pv;break;				
				case 'GEOMETRY[3]':box.style.height=pv;break;
				case 'CHLABEL': case 'FIELDS':box.fillTable(param);break;
				case 'CHWIDTH':box.colResize(param);break;
				case 'RHWIDTH':box.TLCell.style.width=pv;box.BLCell.parentNode.style.width=pv;break;
				case 'CHSTYLE':
					for(var sa in box.myhead){
						box.myhead[sa].style.cssText= 
							'text-align:left;padding:0px;font: 10px sans,arial;border:1px outset #aaaaaa;background-color:#bbbbbb;height:20px;'+
							pv+';width:'+box.myhead[sa].style.width;
					}
					break;
				case 'RHSTYLE':	
					for(sa=0;sa<10;sa++){	
						box.myChead[sa].style.cssText=
							'font: 10px sans,arial;border:0px;border-bottom:1px solid #cccccc;background-color:#eeeeee;'+
							pv+';height:'+box.myChead[sa].style.height+';width:100%;';
					}
					break;			
			}
		}
	}
};