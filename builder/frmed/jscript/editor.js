/*
	str		:	funzione di avvio
	spg		:	Salva la pagina
	rsw		:	funzione che gestisce la barra delle icone
	
*/
edt=new function(){
	stu.stf.edt=1;
	this.str=function(){
		/* crea un canale dati per questa funzione */
		this.chn=new channel();
		this.chn.parent=this;

		this.desSpace=dge('designspace');
		this.dummy=dge('dummy');
		this.offsetTop=this.desSpace.offsetParent.offsetTop;
		this.aar=dge('aar');
		
		/* evento su focus */
		document.onfocus=function onfocus(){
			/* se la finestra corrente è quella già attiva annulla l'operazione di aggiornamento */
			if(window.wid==window.opener.wman.cwnd)return;
			window.opener.wman.cwnd=window;
			/* aggiorna il toolbox dell'albero dei Webgets, se presente */
			window.opener.wman.cfnc('dct.up();','','topan');

		};
		
		/* gestisce gli eventi della tastiera */
		document.onkeydown=function(event){
			/* se preumuto ctrl+s salva il documento */
			if(event.keyCode==83&&event.ctrlKey==true)edt.spg();
			/* se premuto ctrl+a apre l'anteprima */
			if(event.keyCode==65&&event.ctrlKey==true)window.open('../../<?php echo $_SESSION['bld']['root']?>/?'+edt.struct.url.slice(0,edt.struct.url.length-4),'preview','location=no');
			/* se è selezionato un Webget e si preme canc lo elimina */ 
			if(event.keyCode==46)wbt.rmv();
			return false;
		}		
	};

	/* gestisce l'area di notifica */
	this.abm = function(data){
		if(data==null){this.aar.innerHTML='';this.aar.title=null;return;};
		this.aar.innerHTML='<img src="imges/alerticons/'+data.split('|')[0]+'" title="'+data.split('|')[1]+'">';
		/* imposta il richiamo di questa funzione con nessun parametro in modo da cancellare il contenuto */
		setTimeout('edt.abm();',5000);
	};

	/* Carica il form nell'area di lavoro */
	this.lpg=function(src){with(this){
		this.struct = {url:src,root:{},tree:''};	
		struct.root.core=desSpace;
		/* carica la pagina usando il canale dati */
		chn.onload=function(data){
			/* trasforma i dati lineari in un oggetto JS*/
			data=eval(data);
			/* lancia il costruttore di pagina */
			this.parent.bpg(data);
			/* aggiorna il toolbox dell'albero dei Webgets, se presente */
			window.opener.wman.cfnc('dct.up();','','topan');
			/* automatically opens tool panel */
			rfw.wman.own('topan',0,'?17','width=300,height=650,left=20,top=100',0,'forms',0,1);
		};
		chn.url='bdges/xml2js.php';
		chn.get.file=src;
		chn.send();
	}};
	
	/* costruisce realmente la pagina */
	this.bpg=function(obj,tree){
		/* se non è un lancio ricorsivo crea gli oggetti principali */
		if(!tree){obj=obj[1];tree=new Array('root');}
		/* azzera la variabile properties per inserirvi quelle dell'oggetto attuale */
		this.prpts={};
		/* ricerca le proprietà e le acquisisce */
		for(var s in obj){
			if(obj[s].length!=null && s!='TAGTYPE'){
				obj[s] = obj[s].replace(/&amp;/g,'&');
				obj[s] = obj[s].replace(/&gt;/g,'>');
				obj[s] = obj[s].replace(/&lt;/g,'<');
				obj[s] = obj[s].replace(/\\n/g,'\n');
				this.prpts[s] = obj[s];
			}
		}

		this.wenum=wbt.addNew(obj.TAGTYPE,this.prpts,wbt.getOne(tree.join('/')));

		/* ricerca se sono presenti Webgets nidificati e lancia ricorsivamente il parser */
		tree.push(this.wenum);
		for(var s in obj){
			if(obj[s].length==null){this.bpg(obj[s],tree);}
		};
		tree.pop();
		
		
	};

	/* Salva la pagina sul sever */
	this.spg=function(){with(this){
		/* carica la pagina usando il canale dati */
		chn.onload=function(data){edt.abm(data);};
		chn.url='frmed/bridges/frmsv.php';
		chn.post.url=edt.struct.url;
		chn.post.data=edt.bxml();
		chn.send();			
	}};
	/* ritorna il codice xml, per ogni valore di proprietà converte i caratteri XML speciali 
		nei rispettivi codici esadecimali questo è l'ultimo posto dove poter effettuare questa 
		conversione, in quanto dopo, il codice XML sarà trattato	come un unica stringa e non 
		proprietà per proprietà. È possibile passare un oggetto personalizzato.
	*/
	this.bxml=function(page){with(this){	
		if(!page){this.out='';this.tab='';this.prop='';page=edt.struct['root']['en0'];}

		out += tab+'<'+page.box.TYPE+' ';
		
		/* per ogni proprietà effettua le sostituzioni dei caratteri speciali 
			queste devo essere fatte con i valori esadecimali perchè nella funzione
			channel vengono sostituiti i percento % con ilrispettivo codice URL.
			Questi codici verranno scritti così come sostituiti a patto che nell'oggetto
			channel venga prevista la sostituzione del carattere & con il rispettivo
			codice URL, diversamente verranno reinterpretati come erano pima.
		*/			
		for(var s in page.param){
			prop=page.param[s].toString();
			prop=prop.replace(/&/g,'&#38;');				
			prop=prop.replace(/\n/g,'&#13;');
			/* Sostituzione speciale che inganna la catena di invio */
			prop=prop.replace(/</g,'&#60;');
	 		/*prop=prop.replace(/>/g,'&#62;');*/
 			prop=prop.replace(/"/g,'&#34;');/*"*/
 	/*		prop=prop.replace(/\\/g,'%5C');*/
 	/*		prop=prop.replace(/°/g,'sssas&#C2;'); */
			if(prop.length!=0)s!='TAGSPCS'?out+=s+'="'+prop+'" ':null;

		}	

		out += '>\n';

		/* raccoglie i Webgets speciali */
		for(var s in page){

			/* per ogni classe speciale */
			if(s.indexOf('spc')>-1 && page[s]!=null){
				tab+='	';
				this.bxml(page[s]);
				tab=tab.slice(0,tab.length-1);		
			}
		}		

		/* e poi quelli normali */	
		for(var s in page){
			if(s.indexOf('en')>-1 && page[s]!=null){
				tab +='	';
				this.bxml(page[s]);
				tab = tab.slice(0,tab.length-1);
			}
		}

		out += tab+'</'+page.box.TYPE+'>\n';
		/* ritorna la stringa con l'XML di pagina*/
		return out;		
	}};
	
	/* gestore della barra delle icone */
	this.rsw=function(b,target){
		/* resetta il colore di tutti i pulsanti */
		this.rpnt=b.parentNode.parentNode.childNodes;
		for(s=0;s<this.rpnt.length;s++){
			if(this.rpnt[s].firstChild!=null){
				this.rpnt[s].firstChild.style.backgroundColor="lightgray";
			}
		}
		/* colora quello selezionato */
		b.style.backgroundColor="#EBEBEB";
		/* rende invisibili tutte le schede */
		this.rar=document.getElementById('tka').childNodes;
		for(s=0;s<this.rar.length;s++){
			if(this.rar[s].nodeName=="TABLE"){
				this.rar[s].style.display=(this.rar[s].id==target?"block":"none");
			}
		}
	}
};
