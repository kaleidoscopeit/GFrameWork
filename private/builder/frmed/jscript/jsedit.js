/*
	cdh		:	cronologia del codice
 	crh		:	cronologia del cursore
 	hyl		:	livello di annullamento
 	cts		:	can't store ?
 	ear		:	area di testo del codice
 	wop		:	scorciatoia al gestore finestre
 	wpr		:	scorciatoia	all'oggetto window.opener.wman.gwnd('prpty').pty
 	oid		:	id della finestra origine del webget
 	cwb		:	collegamento al webget da modificare
 	pn			:	nome della proprietà
 	pv			:	valore originale della proprietà

*/

/* prepara la pagina */
function init(){
	ear=document.getElementById('ear');
	cdh=new Array();crh=new Array();
	wop=rfw.wman;
	wpr=wop.gwnd('topan').pty;
	oid=wpr.wid;
	pn=wpr.cfld.prpid;
	pv=wpr.cfld.value;	 			
	cwb=wpr.cwb;
	if(!wop.wnd[oid].document){
		alert('Il Form che contiene il Webget è attualmente chiuso.');
		self.close();
	}
	if(!cwb){
		alert('Il Webget a cui si fa riferimento non è accessibile! \nProbabilmente è stato eliminato.');
		self.close();
	}

	cdh.push(pv);
	crh.push('0,0');
	hyl=0;
	cts=0;
	putCode();
}
 		
/* inserisce il codice dell'attuale livello di unDo nella casella di testo */
function putCode(){
	ear.value=cdh[hyl];	 			
	ear.selectionStart=crh[hyl].split(',')[0];
	ear.selectionEnd=crh[hyl].split(',')[1];
}
 		
/* recupera il codice generato e lo invia al webget */
function getCode(){
	/* se il webget selezionato non esiste più */
	wop.wnd[oid].wbt.upy(pn,ear.value,cwb);
	wop.wnd[oid].wbt.upb();
	self.close();
}

/* aggiorna la cronologia unDo in corrispondenza della pressione di caratteri particolari */
function updateHistory(event){
	/* se si modifica il testo ad un livello di unDo il resto della 
		cronologia reDo viene cancellata */
	if(cdh[hyl]!=ear.value){
		while((cdh.length-1)>hyl){cdh.pop();crh.pop();}
	}
}

function editManager(event){
	/* se si preme il tasto TAB viene annullata l'operazione */
	if(event.keyCode==9)return false;

	/* se premuto ctrl+z viene ripetuto l'evento successivo in memoria */
	if(event.keyCode==90&&event.ctrlKey==true&&event.shiftKey==false){
		/* se attualmente ci si trova all'ultimo livello di unDo e rispetto a questo il testo è cambiato 
			memorizza in un nuovo livello il testo attuale e propone il livello attualmente
			congelato come unDo */
		if(hyl==(cdh.length-1) && cdh[hyl]!=ear.value){
			cdh.push(ear.value);
			crh.push(ear.selectionStart+','+ear.selectionEnd);
		} else {
			hyl>0?hyl--:null ;
		}

		putCode();					
		return false;
	}

	/* se premuto ctrl+shift+z viene ripetuto levento precedente in memoria */				
	if(event.keyCode==90&&event.ctrlKey==true&&event.shiftKey==true){
		hyl++;
		if(hyl > cdh.length-1) hyl=cdh.length-1;
		putCode();
		return false;
	}
	
	/* se viene premuto un ctrl+x e esiste un testo selezionto si aggiorna la cronologia */
	if(((event.keyCode==88 && ear.selectionStart < ear.selectionEnd) || event.keyCode==86) && event.ctrlKey==true){
		crh.push(ear.selectionStart+','+ear.selectionEnd);
		cdh.push(ear.value);
		hyl++;
		return;					
	}			
	
	if(event.keyCode==32 || event.keyCode==13){
		crh.push(ear.selectionStart+','+ear.selectionEnd);
		cdh.push(ear.value);
		hyl++;
		cts=0;
		return;
	}
	
	/* se il contenuto, in seguito alla pressione di un tasto qualunque, viene modificato, viene memorizzato
		un nuovo ed unico livello di unDo corrispondente al valore iniziale. Un flag che indica che il testo è cambiato
		viene posta a vero. Questo flag impedisce che venga memorizzato un livello Do per ogni pressione dei tasti
		se non per quelli prescelti */

	if(cts==0 && event.keyCode > 60){
			cdh.push(ear.value);
			crh.push(ear.selectionStart+','+ear.selectionEnd);
			hyl++;
			cts=1;
	}
}
	