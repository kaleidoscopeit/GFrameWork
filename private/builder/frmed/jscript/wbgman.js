/* funzioni che gestiscono i webgets
	-----------------------------------------------------------------------------------------
	
	wbg		:	oggetto principale per la manipolazione dei webget
	rmv		:	funzione che rimuove il webget selezionato o quello specificato nel percorso virtuale
	gne		:	ritorna il webget successivo rispetto a quello specificato o corrente
	gpr		:	ritorna il webget precedente rispetto a quello specificato o corrente
	upy		:	aggiorna il valore di una proprietà del webget specificato o di quello corrente

	oggetti e variabili
	-----------------------------------------------------------------------------------------

	cns		:	flag che determina se la funzione di selezione debba essere bloccata.
	twb		:	wbt temporaneo ad uso interno
	cwb		:	Riferimento al webget corrente
	.prn		:	Riferimento al webget superiore nella struttura ad albero
	.box		:	Riferimento all'oggetto/link all'elemento reale html
	.box.nob	:	Riferimento al nodo contenitore di cui fa parte l'elemento html referenziato da .box
*/

wbt=new function(){
	/* aggiunge alla posizione indicata un nuovo wbt ritorna il numero assegnato
		(l'oggetto è costituito da un involucro 'box' e da uno spazio per inserire elementi figlio 'core')
		nel caso del posizionamento visivo sul form, il wbt che riceve il segnale di insermento
		(onmouseup) provvede a richiamare questa funzione specificando il suo url nel documento
		la funzione aggiungerà il wbt di conseguenza
	*/
	this.addNew=function(type,prpty,vtree){
		/* ottiene il Webget indicato da vtree dove inserire il nuovo */
	//	this.cwb=this.getOne(vtree);
		this.cwb=vtree;
		/* ottiene il numero degli oggetti contenuti per decidere quale numero assegnare al nuovo oggetto */
		this.enum=0;
		for(var s in this.cwb){if(s.slice(0,2)=='en' || s.slice(0,2)=='sp')this.enum ++;};

		/* usa il codice personalizzato di creazione della libreria specificata per ottere l'oggetto da appendere
		gli invia le informazioni sulla posizione attuale */ 
		this.newwbg=libs[type].create(null,prpty);

		/* se il valore ritornato dalla fnzione di creazione è -1 valuta l'errore inviando un messaggio
			e restituisce -1 ad un eventuale funzione chamante */	
		if(this.newwbg==-1){alert('<?php echo $lc_msg['frmed_wbtman_0'];?>');return -1;}

		/* su doppio click apre la finestra delle proprietà */
		this.newwbg.box.ondblclick=function(){
			rfw.wman.own('topan',0,'?17','width=300,height=650',0,'forms',0,1,'pty.upd("'+window.wid+'")');
		};
		/* Aggiunge la funzione di selezione/inserimento nuovo wbt. Ho deciso di inserirla quì in 
			quanto è una funzione universale uguale per tutti i wbt in questo modo si
			risparmia codice.
		*/
		this.newwbg.box.onmousedown=function onmousedown(event){
			/* La varibile 'wbt.reqPlce' determina se si è in modalità piazzamento e contiene
				il nome del wbt da aggiungere. Se si è in questa modalità, viene eseguito codice
				aggiuntivo al fine di valutare se il wbt da inserire sia compatibile con quello
				dove avviene l'evento di pressione. Come noto, l'azione su pressione viene propagata
				risalendo la struttura del DOM, quindi questa funzione ritorna due risultati :
				
				Se il wbt è compatibile lancia la funzione broadcast per annullare la modalità
				inserimento e ritorna falso per bloccare la propagazione dell'evento
				
				in caso contrario non annulla in modo che l'evento, propagandosi, "vada alla ricerca"
				di un vwbt compatibile. 
			*/
			if(wbt.reqPlce){
				/* memorizza le specifiche dei due wbt */
				this.CSPC=libs[this.TYPE].TAGSPCS;
				this.NSPC=libs[wbt.reqPlce].TAGSPCS;
				
				/* esegue dei confronti con le regole di inserimento dichiarate dagli stessi wbt
					se il nuovo wbt puo' essere messo dovunque ed il ricevente accetta tutto */
				this.comp1=(this.NSPC.TARGET=='ALL'&&this.CSPC.ACCEPT=='ALL');
				/* se il ricevente accetta la propria famiglia di wbt ma non se stesso */
				this.comp2=(this.CSPC.ACCEPT=='MYFAMILY'&&this.NSPC.FAMILY==this.CSPC.FAMILY && this.NSPC.TYPE!=this.CSPC.TYPE);
				/* se il ricevente accetta solo un tipo di wbt */
				this.comp3=(this.CSPC.ACCEPT==this.NSPC.TYPE);
				/* se il nuovo wbt richiede di essere inserito in uno specifico wbt */
				this.comp4=(this.NSPC.TARGET.indexOf(this.CSPC.TYPE)>-1?true:false);
				/* se una delle precedenti risulta vera procede con l'inserimento */				
				if(this.comp1||this.comp2||this.comp3||this.comp4){

					/* aggiorna le informazioni sull'offset del wbt contenitore */
					wbt.findOffset(this.nod);
					/* Esegue la vera funzione di inserimento con i parametri della posizione */
					wbt.addNew(
						wbt.reqPlce,
						{GEOMETRY:
								event.clientX-this.offsetLeft-wbt.glbOffsetLeft+'px,'+
								(event.clientY-this.offsetTop-wbt.glbOffsetTop+edt.desSpace.scrollTop+'px')
							
						},
						this.nod
					);
					/* annulla lo stato di attesa di inserimento wbt lanciando in broadcast 
						la funzione	che annulla l'inserimento */
					window.opener.wman.cfnc('wbt.placeStop()','forms');
					
					/* aggiorna il toolbox dell'albero dei wbt, se presente */
					window.opener.wman.cfnc('dct.up();','','topan');
					
					/* blocca la propagazione dell'evento onclick */
					return false;
				}
				return false;
			}

			/* se la funzione continua */
			wbt.select(this.nod);
			wbt.dragStart(event);
		};

		/* aggiunge al nuovo nodo un riferimento al rispettivo nodo contenitore */
		this.newwbg.prn=this.cwb;

		/* aggiunge al box del nuovo webget il riferimento al proprio nodo-contenitore nella struttura del formulario */
		this.newwbg.box.nod=this.newwbg;
		
		/* appende all'albero del documento il nuovo wbg compiendo azioni differrenti in base al tipo (normale o speciale) */
		if(libs[this.newwbg.box.TYPE].TAGSPCS.SPEC==true){
			edt.struct.root.en0['spc'+this.enum]=this.newwbg;
	/*		edt.struct.root.en0['spc'+this.enum].vtree='spc'+this.enum;*/
	/*		dct.addSpecial('spc'+VARS.enum); */
		} else {	
			this.cwb['en'+this.enum]=this.newwbg;
			this.cwb.core.appendChild(this.newwbg.box);
			if(this.newwbg.box.onAppend)this.cwb['en'+this.enum].box.onAppend();
	/*		dct.addNode(STUF.wbt.box.vtree); */
		}
		
		/* resetta il riferimento al wbt corrente per compatibilità con la funzione select */
		this.cwb=null;
		/* ritorna il numero assegnato al wbt corrente alla funzione chiamante */
		return this.enum;
	};

	/* predispone l'editor ad accettare l'inserimento di un nuovo wbt */
	this.placeStart=function(type){
		edt.struct.root.en0.box.style.cursor="crosshair";
		this.reqPlce=type;
	};
	
	/* annulla lo stato di predisposizione all'inserimento di un nuovo wbt */
	this.placeStop=function(){
		edt.struct.root.en0.box.style.cursor="default";
		wbt.reqPlce=null;
	};


	/* elimina il wbt attualmente selezionato o quello specificato in base all'URL virtuale */
	this.rmv=function(cwb){with(this){
		/* se viene specificato un percorso si intende eliminare un wbt arbitrario diversmente
			elimina quello correntemente selezionato
		*/
		if(cwb)this.cwb=cwb;
		/* Se il webget specificato non esiste annulla con un errore */
		if(!cwb){alert('<?php echo $lc_msg['frmed_wbtman_1'];?>');return false};
		/* Se il webget selezionato è la radice del documento annulla con un messaggio */
		if(!cwb.prn.prn){alert('<?php echo $lc_msg['frmed_wbtman_2'];?>');return false}
		/* ottiene il webget contenitore */
		//this.twb=cwb.prn;
		/* rimuove il nodo dal navigatore documento */
		/* richiede conferma dell'eliminazione */
		if(confirm("L'azione rimuovera' il Webget definitivamente.")==1){
			/* rimuove l'oggetto HTML dallo spazio di lavoro */
			if(cwb.box.parentNode)cwb.box.parentNode.removeChild(cwb.box);
			/* annulla il riferimento a questo webget nell'enumeratore nell'albero della pagina */
			for(var s in cwb.prn)if(cwb.prn[s]==cwb)cwb.prn[s]=null;
			/* esegue l'evento su elimina del wbt appena eliminato se esiste */
			if(cwb.box.onRemove)cwb.box.onRemove();;
			/* Azzera i riferimenti ad oggetti HTML */
			cwb=null;twb=null;
			/* aggiorna il toolbox dell'albero dei wbt, se presente */
			window.opener.wman.cfnc('dct.up();','','topan');
		}
	}};

	
	/* funzione che seleziona e attiva uno dei wbt nella pagina */
	this.select=function(vtree,mode){
		/* questo codice ritarda di alcuni millisecondi la possibilità di riselezionare un altro wbt
			si è resa necessaria, in quanto, quando si clicca su una pila di elementi HTML l'evento
			'sulick' si propaga ricosivamente da quello più in avanti fino a quello più in dietro e questo 
			porta alla selezione inevitabile dell'ultimo wbg che lancia questa funzione.
			il codice ritardante abilità un flag che forza l'uscita da questa funzione fino a qunando il 
			click non ha finito di propagarsi.
		*/

		/* esce se il flag è impostato a 'vero' */
		if(this.cns==1){return;}
		/* se non era 'vero' lo imposta ora */
		this.cns=1;
		/* fa partire il reset del flag ritardato */
		window.setTimeout('wbt.cns=0',500);
		/* se l'oggetto corrente è già selezionato esce dalla funzione */
		//this.twb=this.getOne(vtree);
		this.twb=vtree;
		if(this.cwb==this.twb)return false;
		/* resetta l'aspetto del puntatore */
	/*	try{this.cwb.box.style.cursor='';}catch(e){}*/ 
		/* cattura l'oggetto JavaScript */
		//this.cwb=this.getOne(vtree);
		this.cwb=vtree;
		/* crea un floater per evidenziare il wbt selezionato */
		flt.dty('all');
		id=flt.add();
		this.cwb.dum.insertBefore(flt.fls[id],this.cwb.dum.firstChild);
		/* aggiorna il box delle proprietà */
		this.upb();
		/* porta il focus su il dummy in quanto, a volte, il giro delle funzioni non porta il focus sul documento */
		edt.dummy.focus();
		/* abilita le funzioni sulla pressione dei tasti solo se la zona attiva è quella di editing e non si è alla radice del documento */
		edt.canRmvWbg=true;
	};

	/* funzione che lancia la funzione di aggiornamento delle proprietà assiciate al webget 
		corrente, questo potrebbe influenzare l'aspetto, dipende dal tipo di proprietà
	*/
	this.upy=function(pn,pv,wb){with(this){
		/* Se la prorietà è l'ID verifica che non esista un altro wbt con lo stesso id */
		if(pn=='ID'){if(gbi(pv))alert('<?php echo $lc_msg['frmed_wbtman_3'];?>')};
		/* ottiene il webget da aggiornare o direttamente o tramite vtree */
		if(!wb&&cwb)wb=cwb;
		/* Esegue la funzione di aggiornamento specifica per il webget scelto */
		libs[wb.box.TYPE].updateProperty(wb,pn,pv);
		/* aggiorna l'albero dei wbt*/
		if(pn=='ID'){window.opener.wman.cfnc('dct.up();','','topan')};
		/* resetta twb */
	}};
	
	/* ritorna il valore della proprietà specificata del webget correne.
		la ho creata perchè eventuali dialogi nelle proprietà di un wbt potrebbero aver bisogno
		dei valori delle altre proprietà per funzionare correttamente
	*/
	this.getPrpty=function(prptyName){
		return(this.cwb.param[prptyName]);
	};
	
	/* aggiorna il box delle proprietà */
	this.upb=function(){
		window.opener.wman.cfnc('pty.upd("'+window.wid+'");','','topan');
	};

	/* Ottiene, cercandolo ricorsivamente, un wbt in base alla proprietà ID
		i		:	ID del wbt da cercare
		r		:	ramo da analizzare modificato ricorsivamente
	*/
	this.gbi=function(i,r,s,o){with(this){
		if(!r){r=edt.struct['root']['en0'];}

		/* verifica se l'attuale webget ha l'ID richiesto */ 
		if(r.param)if(r.param['ID']==i)return r;

		/* per ogni webget contenuto rilancia questa funzione */
		for(s in r){
			if(s.slice(0,2)=='en'||s.slice(0,3)=='spc')if(o=this.gbi(i,r[s]))return o;
		}
		
		return false;
	}};
	
	/* funzione riutilizzabile che ritorna un wbt in base al virtualTree */
	this.getOne=function(vtree){
		/*	ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root */
		this.tree=vtree.split('/');
		this.nodeid=this.tree.pop();
 		this.tree=this.tree.join('.en');

		/*identifia se si tratta di un nodo normale o uno speciale */ 	
		if(this.nodeid=='root')return edt.struct.root;
 		if(this.twb=eval("edt.struct."+this.tree)['en'+this.nodeid])return this.twb;
 		if(this.twb=eval("edt.struct."+this.tree)['spc'+this.nodeid])return this.twb;
 		
 		return false;
	};
	
	/* funzione riutilizzabile che ritorna il wbt successivo rispetto a quello corrente o quello specificato nel vTree */
	this.gne=function(wb){with(this){
		if(!wb&&cwb)wb=cwb;
		/* tenta di catturare il wbt in base al percorso o a quello attualmente selezionato. */
		if(!wb)return false;
		/* verifica che esista un wbt successivo */
		if(!wb.box.nextSibling)return false;
		/* ritorna il nuovo wbt */
		return wb.box.nextSibling.nod;		
	}};
	
	/* funzione riutilizzabile che ritorna il wbt successivo rispetto a quello corrente o quello specificato nel vTree */
	this.gpr=function(wb){with(this){
		if(!wb&&cwb)wb=cwb;
		/* tenta di catturare il wbt in base al percorso o a quello attualmente selezionato. */
		if(!wb)return false;
		/* verifica che esista un wbt successivo */
		if(!wb.box.previousSibling)return false;
		/* ritorna il nuovo wbt */
		return wb.box.previousSibling.nod;	
	}};
	
	/* trova la somma dello spostamento da sinistra e dall'alto totale di tutti i contenitori del wbt specificato
		deposita le variabili in offset nell'oggetto wbt
	*/
	this.findOffset=function(Wbg){
		this.TOBJ=Wbg.box.offsetParent;
		this.glbOffsetLeft=0;
		this.glbOffsetTop=0;

		while(this.TOBJ.offsetParent){
			this.glbOffsetLeft+=this.TOBJ.offsetLeft;
			this.glbOffsetTop+=this.TOBJ.offsetTop;
			this.TOBJ=this.TOBJ.offsetParent;
		}
	};
};