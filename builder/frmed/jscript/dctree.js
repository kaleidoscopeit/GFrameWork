/*
	Gestore del menu' ad albero che rappresenta la struttura del form selezionato

	dcr		:	funzione scorciatoia per creare un elemento
	dge		:	funzione scorgiatoia per catturare un elemento HTML
	dns		:	div contenente la struttura della pagina
	dnx		:	div contenene i webget speciali (sock,form....)
	dsb		:	pulsante usato per visualizzare la scheda dns
	dxb		:	pulsante usato per visualizzare la scheda dnx
	dsh		:	elemento di evidensiazione per l'albero webget
	dxh		:	elemento di evidenziazione per l'albero dei webget speciali
	trr		:	radice dell'albero dei webget
	rwd		:	riferimento alla finestra corrente
	cwb		:	webget corrente
	lkt		:	link nel nodo corrispondente al webget reale con il nodo del menù ad albero
	nmd		:	Usato da scn e an per determinare se l'elemento da aggiungere è un ramo o un nodo
	
	funzioni

	dct		:	oggetto principale	
	st			:	funzione di avvio
	an			:	aggiunge un nodo
	as			:	aggiunge un nuovo nodo speciale
	swc		:	gestisce la selezione delle schede visualizzate
	scn		:	funzione che scansiona la struttura della pagina e lancia an o as
*/

dct = new function(){
	this.st=function(){
		/* Elementi HTML usati per l'interazione */
		this.dsb=dge('dsb');
		this.dxb=dge('dxb');
		/* acquisisce le aree del navigatore del documento */
		this.dns=dge('dns');
		this.dnx=dge('dnx');
		/* crea le due barre di evidenziazione per le voci del navigatore document */
		this.dsh=dcr('div');
		this.dsh.style.cssText='position:absolute;width:100%;height:20px;top:-20px;background-color:lightgray;';
		this.dns.appendChild(this.dsh);
		this.dxh=dcr('div');
		this.dxh.style.cssText='position:absolute;width:100%;height:20px;top:-20px;background-color:lightgray;';
		this.dnx.appendChild(this.dxh);
		/* aggiunge le definizioni di alcune variabili utili */
		this.li='';this.table='';this.tr='';this.td='';this.img='';this.ul='';this.s='';this.ss='';this.arrw='';
		
		/* crea la radice dell'albero dei webget per larea specificata */
		this.trr=dcr('ul');
		this.trr.style.cssText = "margin-left:-20px;margin-top:5px;position:absolute;";
		this.dns.appendChild(this.trr);
		/* aggiorna la visualizzazione sull'apertura */
		this.up();
	};
	
	/* lancia l'aggiornamento ricorsivo della struttura dell'albero */
	this.up=function(){
		/* annulla l'opzione che abilita la possibilià di cancellare un webget sul form selezionato */
		/*this.canRmvWbg=false;*/
		/* cancella il contenuto dell'albero visualizzato */
		this.trr.innerHTML='';
		this.dnx.innerHTML='';
		this.dnx.appendChild(this.dxh);
		/* ottiene il riferimento alla finestra da analizzare come oggetto interno */
		this.rwd=window.opener.wman.cwnd;
		/* Se per qualche motivo non esiste l'editor nella finestra corrente annulla */
		if(!this.rwd.edt)return;
		/* associa la radice della lista al contenitore root nell'albero dei webget */
		this.rwd.edt.struct.root.lkt=this.trr; 
		/* lancia la scansione partendo dalla radice dell'albero dei webget */
		this.scn(this.rwd.edt.struct.root);
	};
	
	/* scansiona ricorsivamente l'albero delegando alle funzioni specifiche la creazione dei puntatori ai webget
		contenuti in questa pagina
		
		stc		:	collegamento alla struttura del form corrente
		 
	
	*/
	this.scn=function(stc,s,i){with(this){
		/* analizza la struttura dei Webgets in cerca di nodi figlio di tipo normale */
		for(s in stc){
			/* se lo trova e non è uno cancellato... */
			if(s.slice(0,2)=='en'&&stc[s]){
					/* imposta il webget corrente come variabile dell'oggetto */
					this.cwb=stc[s];
					/* verifica se in esso esistono ulteriori webget attivi nidificati... */
					for(i in cwb){if(i.slice(0,2)=='en')this.nmd=1;}
					/* lancia la funzione che aggiunge il nodo nella posizione specificata */
					this.an(cwb);
					/* rilancia la scansione partendo da il webget corrente */
					this.scn(this.cwb);
			}
			if(s.slice(0,3)=='spc' && stc[s]){
					/* imposta il webget corrente come variabile dell'oggetto */
					this.cwb=stc[s];
					/* lancia la funzione che aggiunge il nodo nella posizione specificata */
					this.as(cwb.box.vtree);
			}
		}
	}};

	/* aggiunge un nuovo nodo all'albero dei webgets */
	this.an=function(v){with(this){
			/* crea il nodo li da aggiungere all'albero HTML */
			li=dcr('li');
			li.style.cssText="list-style-type:none;margin-left:-20px;margin-top:-5px;cursor:pointer";
			table=dcr('table');li.appendChild(table);
			/* imposta l'URL del nodo corrente come variabile interna allo stesso */
			table.v=v;
			/* gestisce l'area che evidenzia la selezione */
			table.onmousedown=function(){
				dsh.style.top=this.offsetTop+6+"px";
				dsh.style.height=this.offsetHeight-4+"px";
				return false;
			};
			/* seleziona il webget collegato	*/
			table.ondblclick=function(){rwd.wbt.select(this.v)};
			
		
			/* genera l'icona ed il testo nell'albero */	
			tr=dcr('tr');table.appendChild(tr);
			td=dcr('td');tr.appendChild(td);
			td.vAlign="bottom";
			td.style.cssText ="width:20px;";
			td.noWrap=1;	
			arrw=dcr('img');td.appendChild(arrw);
			arrw.border="0";arrw.status="hid";
			this.nmd==1?arrw.src="imges/arrow_close.png":arrw.src="imges/dot7.png";
			/*if(this.NMode==1)arrw.onclick=function onClick(){dct.visiMan(this);return false;};*/
			img=dcr('img');td.appendChild(img);
			img.border="0";img.width="16";
			img.src='?101&f=i&c='+this.rwd.libs[cwb.box.TYPE].TAGSPCS.FAMILY+'&l='+cwb.box.TYPE;
			td=dcr('td');tr.appendChild(td);
			td.style.fontSize = "12px";
			td.innerHTML=cwb.box.TYPE+(cwb.param.ID?'('+cwb.param.ID+')':'')+(cwb.hid?'(!)':'');
			td.title=(cwb.hid?'Attualmente nascosto.':'');
			
			ul=dcr('ul');li.appendChild(ul);

			/*	Questi due comandi associano l'elemento creato al rispettivo webget reale e 
				accodano il nuovo nodo del menù ad albero a quello precedente.
				Questo approccio consente di sfruttare la già esistente struttura nidificata
				dell'editore dei form e riduce la quantità di codice da generare.
				Comunque questo approccio non mi piace, vedremo se si rivelerà utile in futuro
			*/
			/* associa il nuovo nodo di lista al suo cossispettivo webget nell'albero principale */
			this.cwb.lkt=ul;

			/*  accoda l'elemento lista nel nodo collegato al webget parent di quello referenziato */
			this.cwb.prn.lkt.appendChild(li);

			/* azzera il modo di inserimento nodo */			
			this.nmd=0;

	}};

	/* genera il codice che disegna i webgets speciali e li aggiunge direttamente nell'area dedicata */
	this.as=function(v){with(this){
		this.s='';
		
		/* analizza ciclicamente le voci in radice dell'albero ed identifica quelle voci con prefisso sp
			a quel punto aggiunge l'oggetto HTML */
		this.table=dcr('table');
		table.style.cssText='position:relative;cursor:pointer';
		table.v=v;
		/* Crea un collegamento al webget referenziato */
		table.wbt=cwb;
		table.onmousedown=function(){
			/* sovrappone la barra di evidenziazione */
			dct.dxh.style.top=this.offsetTop+1+'px';
			dct.dxh.style.height=this.offsetHeight-3+'px';
			/* seleziona il webget all'interno del form corrente */
			dct.rwd.wbt.cwb=this.wbt;
			dct.rwd.wbt.upb();
			dct.canRmvWbg=true;
			/* annulla le eventuali intercazioni con l'HTML */
			return false;
		};

		this.tr=dcr('tr');table.appendChild(tr);
		this.td=dcr('td');tr.appendChild(td);
		td.vAlign="top";
		td.style.cssText="width:20px;";
		td.noWrap=1;	
			
		this.img = dcr('img');td.appendChild(img);
		img.border="0";img.width="16";		
		img.src='?101&f=i&c='+this.rwd.libs[cwb.box.TYPE].TAGSPCS.FAMILY+'&l='+cwb.box.TYPE;
		this.td = dcr('td');tr.appendChild(td);
		td.style.fontSize = "12px";
		td.innerHTML=cwb.box.TYPE+(cwb.param.ID ? '('+cwb.param.ID+')' : '');

		this.dnx.appendChild(table);
	}};


	/* gestisce lo swap delle due schede dell'albero del documento (normali e speciali) */
	this.swc=function(c,b){
		this.dsb.style.backgroundColor = '#E5E5E5';
		this.dxb.style.backgroundColor = '#E5E5E5';
		b.style.backgroundColor = 'lightgrey';
		
		if(c=='dns'){this.dns.style.visibility='visible';this.dnx.style.visibility='hidden';}
		else {this.dns.style.visibility='hidden';this.dnx.style.visibility='visible';}
	};
};
