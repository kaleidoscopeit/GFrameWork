/* Gestisce in modo uniforme le finestre che vengono aperte durante la sessione di lavoro
	e le rispettive interazioni.

	Sono integrate funzioni standard per aprire i tipi di finestre più comuni e organizzarle
	in oppurtune famiglie.(Forms, Reports, Pannelli, Strumenti)

	È possibile aprire finestre arbitrarie e associarle ad una famiglia con nome arbitrario.
		
	È possibile chiamare funzioni in broadcast su tutte le finestre o su famiglie.
	
	È possibile creare vincoli fra finestre in modo che se viene chiusa la referenziata,
	vencano chiuse anche quelle collegate.
	
	Finestre di tipo modale che congelano il contenuto della finestra chiamante.
	
	wnd			:	array contenente lo stack delle finestre
	this.own		:	funzione principale per l'apertura delle finestre
	this.chkown	:	funzione che verifica se una finestra è già aperta
	
	Parametri di "this.own"
	
	ATTENZIONE:	Questa funzione deve essere inclusa solo nella prima finestra, quella principale del
					progetto, la finestra principale non deve essere mai chiusa, pena lo scollegamento
					delle altre finestre dipendenti.
					
	TODO :	Sarebbe interessante creare un window manager che non sia dipendente da una finestra principale
				e che funzioni tipo le reti serverless. Vedremo! per ora afinerò questo sistema.
*/

wman=new function(){
	this.i=0;
	/* Istanzia l'oggetto interno alla funzione usato per indicizzare le finestre
		Nel contempo inserisce come finestra main quella in cui è inclusa questa funzione */ 
	this.wnd=new Array('foo');
	
	/* apre una finestra gestita dal codice
		cbf :	funzione di callback da eseguire quando è aperta la finestra
				ATTENZIONE :	questa deve richiamare funzioni generiche o broadcast come "this.cfnc()"
									nel window manager.
	*/
	this.own=function(nme,fly,url,opt,prt,fdp,mdl,ock,cbf){with(this){
		if(ock){
			for(i=0;i<wnd.length;i++){
				if(wnd[i].nme==nme){
					ock!=1?alert(ock):0;
					wnd[i].focus();
					return(0);
				}
			}
		}

		if(mdl&!prt)return(alert('<?php echo $lc_msg['winman_0']?>'+' if mdl:prt!=null'));
		this.cwnd=window.open(url,nme,opt);
		cwnd.wman=new Array();
		this.regw(cwnd,nme,fly,prt,fdp);
		cwnd.wman.cbf=cbf;
		cwnd.url=url;
		this.cwnd.focus();
	}};


	/* registra una finestra presso il gestore finestre */
	this.regw=function(cwnd,nme,fly,prt,fdp){
		this.wnd.push(cwnd);
		cwnd.wid=this.wnd.length-1;
		cwnd.nme=(nme?nme:null);
		cwnd.fly=(fly?fly:null);
		cwnd.prt=(prt?prt:null);
		cwnd.rfw=window;		
		cwnd.fdp=(fdp?fdp:null);		
		/* DIV oscuratore per le finestgre di dialogo */
		cwnd.shd=cwnd.document.createElement('div');
		cwnd.shd.onmousedown=function(){return false};
		cwnd.shd.style.cssText="-moz-opacity:.25;position:absolute;left:0px;top:0px;width:100%;height:100%;background-color:black;visibility:hidden;";
		cwnd.onunload=function(){if(((this.document.location+'').indexOf(this.url)!=-1))this.rfw.wman.chkodp(this.wid)};


		/* gestore degli eventi di pressione tasti */
		
		/* oggetto contenente il codice tasto e l'evento da eseguire. A questo array i vari webgets possono
		   collegare funzioni personalizzate in base ai tasti premuti a livello globale */
		   
		/* Valutare la possibilità di inserire delle funzioni di default da ripristinare con un semplice comando
			e per esempio delle funzioni da associare una volta solamente e poi ritornare alla default */ 
		cwnd.kfn={'116':'false'}; 
		cwnd.onkeydown=function(event){
			for(i in this.kfn){if(i==event.keyCode)return(eval(this.kfn[i]))}
		}		
	};

	/* Funziene da eseguire in chiusura di una finestra che verifica se tutte le finestre
		della famiglia a cui appartiene sono state chiuse. In caso positivo, 
		vengono chiuse tutte	le finestre che dipendono dalla famiglia.
	*/
	this.chkodp=function(wid){with(this){
		/* Verifica se ci sono finestre dipendenti da questa e le chiude */
		for(i=0;i<wnd.length;i++){if(wnd[i].prt==wnd[wid].nme)wnd[i].close()}
		
		/* scansiona tutte le finestre aperte per verificare se ne esiste almeno una della 
			famiglia specificata. In caso affermativo esce silenziosamente dalla funzione. */
		for(i=0;i<wnd.length;i++){if(i!=wid&&wnd[i].fly==wnd[wid].fly)return false;}

		/* scansiona tuttle le finestre aperte e quando ne trova una che dipende dalla famiglia
			specificata la chiude. */
		for(i=0;i<wnd.length;i++){if(wnd[i].fdp!=0&wnd[wid].fly!=0&wnd[i].fdp==wnd[wid].fly)wnd[i].close()}
	}};

	/* Verifica se una finestra è già stata aperta (ritorna la finestra se trovta)*/
	this.chkown=function(nme){with(this){
		for(i=0;i<wnd.length;i++){if(wnd[i].nme==nme)return(wnd[i]);}
	}};

	/* Funzione scorciatoia che apre una finestra dell'editor dei form con all'interno il form richiesto. */
	this.ofr=function(frm){with(this){
		/* apre la finestra */
		this.own(frm,'forms','?10&f='+frm,'location=false,menubar=false,left='+(self.screenX+40)+',top='+(self.screenY+40),0,0,0,'<?php echo $lc_msg['winman_1']?>');
	}};
	

	/* Funzione scorciatoia che apre una finestra di dialogo modale
		
		url		:	indirizzo della finestra (richiesto)
		opt		:	opzioni del metodo window.open (opzionale)
		prt		:	nome della finestra dalla quale dipende la nuova (opzionale) 
						(se impostato, alla chiusura della finestra principale anche questa si chiuderà)
					
	*/	
	
	this.odl=function(url,prt,opt){with(this){
		this.own(url,'dialogs','?'+url,'menubar=0,location=0,resizable=0,scrollbars=0,status=0,'+opt,prt,0,1,1);
		this.shd=this.gwnd(prt).shd;
		this.shd.style.visibility='visible';
		this.shd.odl=url;this.shd.wman=this;
		this.shd.onclick=function(){this.wman.gwnd(this.odl).focus()};
		this.gwnd(prt).document.body.appendChild(this.shd);		
		this.gwnd(url).onbeforeunload=function(){this.rfw.wman.shd.style.visibility='hidden'};
		/* chiude la finestra con ESC : la sciare così perchè ci sono dei problemi di protezione sugli script */
		this.gwnd(url).kfn['27']='this.rfw.wman.wnd[this.wid].self.close()';
	}};

	/* Funzione da inserire nel onFocus di una finestra che preveda dialoghi modali.
		Quando eseguita, verifica, per tutte le finestre indicizzate, se è stata aperta
		una modale per la finestra chiamante. Quando la trova esegue una funzione di riapertura
		che riporti in primo piano la finestra modale.
	*/
	this.chkmdl=function(prnt){with(this){
		/* for each indexed windows do checks */
		for(i=0;i<wnd.length;i++){
			if(wnd[i].prnt==prnt & wnd[i].modl==true){wnd[i].focus();return false}
		}		
	}};

	
	/* esegue la funzione specificata su tutte le finestre aperte o una famiglia specifica delle stesse
	
		fnc		:	funzione da lanciare nelle finestre coinvolte
		fly		:	familgia di finestre da coinvogere
		 
 	*/
	this.cfnc=function(fnc,fly,nme){with(this){
		/* se non viene specificata una funzione annulla */
		if(!fnc){alert('<?php echo $lc_msg['winman_2']?>');return;};

		/* se viene specificata una finestra esegue la funzione solo per quella 
			e ritorna il valore restituito */
		if(nme){
			for(i=0;i<wnd.length;i++){
				if(wnd[i].nme==nme){
					return wnd[i].eval(fnc);
				}
			}
			return;
		}

		/* se è specificata una famiglia viene eseguita selettivamente su ogni finestra di quella famiglia */
		for(i=0;i<wnd.length;i++){if(wnd[i].fly==fly)wnd[i].eval(fnc);}
	}};

	/* ritorna il riferimento alla prima finestra, in ordine di apertura, con il nome specificato*/
	this.gwnd=function(nme){
		if(!nme)return false;
		for(i=0;i<this.wnd.length;i++){if(this.wnd[i].nme==nme)return this.wnd[i];}
	};
	
	/* funzione che gestisce la chiusura di una finestra gestita.iude tutte le finestre dipendenti. È necessario collegarla all'evento
		onclose sulla finestra principale. 
	*/
	this.ocl=function(){};

	/* utilità per il ridiminesionamento delle finestre */
	this.rsz=function(wid,w,h){
		/* se manca uno dei valori esce */
		if(!wid|!w|!h)return;
		/* ridimensiona la finestra ad una dimensione nota per dedurre le misure esterne
		rispetto a quelle interne */
		this.wnd[wid].resizeTo(w,h);
		this.wnd[wid].resizeTo(w-0+(w-document.body.offsetWidth),h-0+(h-document.body.offsetHeight));
}
};