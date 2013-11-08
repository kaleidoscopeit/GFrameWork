{
/* FTABLE */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'FTABLE',TARGET:'ALL',FAMILY:'FREETABLE',ACCEPT:'FTAREA',CANDRAG:true};
	
	this.create=function(vtree,prpty){with(this){
		/* se alcune proprietà necessarie sono mancanti imposta quelle di default */
		this.dfl={
			'GEOMETRY[0]':'0px',
			'GEOMETRY[1]':'0px',
			'GEOMETRY[2]':'300px',
			'ROWHEIGHT':'50px',
			'ROWS':'2',
			'COLS':'2',
			'STYLE':''
			};

		prpty.GEOMETRY=prpty.GEOMETRY.split(',');
		for(var i in dfl){if(!eval('prpty.'+i))eval('prpty.'+i+'=dfl[i]');};

		this.box = dcr('div');
		box.TYPE = TAGSPCS.TYPE;
		box['vtree'] = vtree;
		box.style.cssText ="position:absolute;overflow:auto;border: 1px dotted blue;"+
		'left:'+prpty.GEOMETRY[0]+
		';top:'+prpty.GEOMETRY[1]+
		';width:'+prpty.GEOMETRY[2]+
		';height:'+prpty.GEOMETRY[3]+';';

		box.table = dcr('table');box.appendChild(box.table);
		box.table.style.cssText = "width:100%;";
		box.table.border = 1;box.table.cellPadding = 0;box.table.cellSpacing = 0;
			
		/* crea un core fasullo */
		box.core={};
		box.core.box=box;
		/* crea il contenitore temporaneo delle aree presenti nella tabella*/
		box.ware=dcr('div');
					
		box.fillTable=function(){
			/* se son presenti aree definite, queste vengono rimosse dalla tabella e sistemati in un
			contenitore sicuro per essere poi rimesse quando la tabella è stata aggiornata nel caso in
			cui l'oggetto sia marcato come cancellato non viene riposizionato*/
			if(this.core.areas){
				for(var sub in this.core.areas)if(this.core.areas[sub].deleted!=true)this.ware.appendChild(this.core.areas[sub]);
			};
			/* elimina il primo oggetto che è il tbody per vuotare la tabella*/
			if(this.table.firstChild)this.table.removeChild(this.table.firstChild);
			/* ricrea un nuovo tbody*/
			this.tbody = dcr('tbody');this.table.appendChild(this.tbody);
			/* esegue diverse operazioni di disegno in base al metodo di linea scelto (quantità o senza limite) */
			switch(prpty.RSLIMIT=='true'){
				case false:
					/* disegna le righe e le colonne */
					for(this.y=0;this.y<prpty.ROWS;this.y++){
						this.tr=dcr('tr');this.tbody.appendChild(this.tr);
						for(this.x=0;this.x<prpty.COLS;this.x++){
							this.td=dcr('td');this.tr.appendChild(this.td);
							this.td.style.height=prpty.ROWHEIGHT;
							this.td.width=(100/prpty.COLS)+'%';
							if(this.ware.firstChild)this.td.appendChild(this.ware.firstChild);
							this.core.areaidx++;	
						}
					}
					/*verifica se sono presenti aree non visualizzate*/
					if(this.ware.firstChild){alert('Attenzione, il numero di aree definite eccede il numero di celle. Per evitare problemi incrementare il numero di celle.');}
				break;
				
				case true:
					this.repeat='true';
					/* comincia a disegnare le righe inserendo all'interno le celle */
					/* la prima riga viene comunque renderizzata, ma se non esistono altre aree definite */
					/* l'esecuzione termina */
					while(this.repeat=='true'){
						this.tr=dcr('tr');this.tbody.appendChild(this.tr);	
						for(this.x=0;this.x<prpty.COLS;this.x++){
							this.td=dcr('td');this.tr.appendChild(this.td);
							this.td.height=prpty.ROWHEIGHT;
							this.td.width=(100/prpty.COLS)+'%';
							if(this.ware.firstChild)this.td.appendChild(this.ware.firstChild);
							this.core.areaidx++;
						}
						if(!this.ware.firstChild)this.repeat=0;
					}
				break;
			}
			/* resetto l'indice delle celle per usarlo come puntatore per l'inserimento delle aree*/
			this.core.areaidx=0;
		};
		/* funzione specchietto che gestisce l'inserimento di nuove aree*/
		box.core.appendChild =function(obj){
			/* se mai creato, crea un oggetto con all'interno i riferimenti alle aree contenute nella tabella*/
			if(!this.areas)this.areas={};
			/* aggiunge l'elemento referenziato all'interno dell'oggetto*/
			for(var sub in this.areas);
			this.areas[sub+1]=obj;
			this.box.fillTable();
		};
		/* funzione specchietto che gestisce il box di evidenziazione*/		
		box.core.insertBefore=function(obj,ref){this.box.insertBefore(obj,ref);};
	
	this.param=prpty;
	box.props=param;
	/* lancia subito il riempimento tabella*/
	box.fillTable(prpty);
	return {box:box,core:box.core,dum:box,param:param};
	}};

	/*aggiorna le proprietà del webget e la visualizzazione*/	
	this.updateProperty=function(CWbg,PName,PVal){

		/*modifica il valore memorizzato della proprietà corrente*/
		eval('CWbg.param.'+PName+' = PVal;'); 

		/*esegue un azione differente in base a quale proprietà è stata cambiata*/
		with(CWbg){
			switch(PName){
				case 'STYLE' : box.style.cssText += PVal; break;
				case 'GEOMETRY[0]' : box.style.left = PVal;break;				
				case 'GEOMETRY[1]' :	box.style.top = PVal;break;				
				case 'GEOMETRY[2]' :	box.style.width = PVal;break;				
				case 'GEOMETRY[3]' :	box.style.height = PVal;break;
				case 'ROWS' : box.fillTable();break;
				case 'COLS' : box.fillTable();break;
				case 'ROWHEIGHT' : box.fillTable();break;
				case 'LIMIT' : box.fillTable();break;
			}
		}
	}	
};