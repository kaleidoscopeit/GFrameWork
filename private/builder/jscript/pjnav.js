/* Questa funzione crea un DIV esteso che si occupa di creare una struttura ad albero al suo interno,
	e di gestirla. Il contenuto rispecchia la struttura del files a partire dall'url specificato. 
	Nello specifico, questo oggetto chiama la sua controparte residente sul server che trasforma 
	la struttura della directory scelta in un oggetto javascript (JSON) il quale verrà interpretato.
	Questa funzione dipende dalla libreria channel. 

	url	: percorso della directory voluta (se vuoto parte da radice)  
	ock	: azione da eseguire sul singolo click del nodo
	odck	: azione da eseguire sul doppio click del nodo
	lbl	: etichetta da inserire nel nodo principale
	opn	: array contenente la lista dei nodi aperti usate per il metodo update
	lmi	: link per la corrispondenza mime->icona estensibile dall'esterno
	thm	: tema da utilizzare
	crl	: valore dinamico dipendente dall'elemento selezionato che esprime l'url dell'elemento
	cit	: nome dell'attuale elemento se non si tratta di un nodo

	sts	: stato del nodo : 0 chiuso, 1 aperto	
	ogni nodo possiede una variabile interna (this.url) che corrisponde
	alla parte ulteriore da accodare a tutti i prefissi inviati
*/
	

pjnav=function(id){
with(this){
	this.obj=dcr('div');
	obj.chn=new channel();
	obj.chn.onload=function onload(data){this.parent.build(data);};
	obj.chn.parent=obj;
	obj.dirhl=dcr('div');obj.appendChild(obj.dirhl);
	obj.dirhl.style.cssText='position:absolute;width:100%;height:20px;background-color:lightgray;';	
	obj.cont=dcr('ul');obj.appendChild(obj.cont);
	obj.cont.style.cssText='margin-left:-20px;margin-top:4px;position:absolute;';
	obj.opn=new Array;
	obj.lmi={'inode':'folder.png','':'text-x-generic.png','xml':'form.png'};
	/*obj.thm='default';*/
	
	obj.update=function(){
		this.style.cssText+="overflow:auto;position:absolute;";
		this.chn.get['url']=this.uri;
		this.chn.url='bdges/pjdir2js.php';
		this.chn.send();
	};
	
	obj.build=function(pjtree){with(this){
		this.ul='';this.li='';this.td='';this.tr='';this.table='';
		this.arrw='';this.img='';this.url=new Array;this.count=0;
		this.tree=new Array;this.s=0;
		this.pjtree=eval(pjtree);
		cont.innerHTML='';
		/* per ogni elemento trovato lo mette in lista */
		for(i in pjtree){cont.appendChild(makeTree(pjtree[i]))}
		delete pjtree;
	}};


	obj.makeTree=function(curr){
	with(this){
		/* crea l'elemento della lista da inserire nella struttura */
		li=dcr('li');
		li.onmousedown=function(){return false;};
		li.style.cssText="list-style-type:none;margin-left:-20px;margin-top:-5px;";
		table=dcr('table');
		li.appendChild(table);
		li.sts=0;

		tr=dcr('tr');
		tr.style.cssText="cursor:pointer;font-size:12px;";
		table.appendChild(tr);
		
		td=dcr('td');
		td.noWrap=1;
		td.vAlign="bottom";
		tr.appendChild(td);
	
		arrw=dcr('img');
		arrw.border="0";
		td.appendChild(arrw);
	
		img=dcr('img');
		img.border="0";
		td.appendChild(img);
		
		td=dcr('td');
		td.noWrap=1;
		td.innerHTML=curr.shift();
		tr.appendChild(td);

		table.parent=arrw.parent=this;
		/* Aggiunge l'elemento HTML appena creato infondo al puntatore */	
		tree.push(li);
		/* imposta l'URL del nodo corrente come variabile interna allo stesso per l'interazione */
		li.url=table.itemURL=url.join('/');
		/* imposta il nome e il mime dell'item corrente */
		table.itemName=td.innerHTML;table.itemMime=curr[0];
		/* accoda il testo dell'elemento attuale contenuto in curr.tgt per ricostruire l'URL */
		url.push(td.innerHTML);		
		/* completa l'URL nel li per eseguire correttamente gli automatismi di apertura/chiusura */
		li.url+='/'+table.itemName;
		
		/* per ogni associazione mimie->icona trova la corrispondenza e piazza l'icona */
		if(lmi[curr[0]])img.src='imges/pjnav/16/'+lmi[curr[0]];
		else img.src='imges/pjnav/16/text-x-generic.png';

		curr.shift();

		/* Itentifica se è un nodo o un elemento e gli attacca le funzioni specifice
			il nodo ha in curr[0] il primo elemento nidificato */
		if(curr[0]){
			/* nodo */
			arrw.src="imges/arrow_close.png";
			for(s=0;s<opn.length;s++){
				if(opn[s]==li.url){
				/* cur è l'unico riferimento locale per ogni elemento nidificate che viene
					elaborato e quindi si puo' avere la certezza che si riferisca a questo
					ciclo di analisi 
				*/
				li.sts=curr.sts=1;
				/* cambia la freccia ad open */
				arrw.src="imges/arrow_open.png";
				}
			}
			/* esegue la vuzione di visualizzazione sul nodo */
			arrw.onclick=function onClick(){this.parent.visiMan(this);};
		} else{
			/* elemento */
			arrw.src="imges/dot7.png";
			/* prova ad eseguire l'evento su doppio click */
			table.ondblclick=function(){try{this.parent.odck()}catch(e){}}
		}

		/* attacca la funzione su click al nodo : assegna i puntatori attuali, sposta la barra
		di evidenziazione sul nodo e se presente esegue la funzione personalizzata ock*/
		table.onclick=function(){with(this){
			parent.itemName=itemName;
			parent.itemURL=itemURL;
			parent.itemMime=itemMime;
			parent.dirhl.style.top=offsetTop+5+"px";
			try{parent.ock()}catch(e){};
		}};
					
		/* analizza il contenuto dell'oggetto passato per verificare se esistono altri 
		elementi nidificati : il codice precedente a tolto i primi due elementi dell'array
		ed ora rimangono solo eventualmente quelli che ne contengono altri.
		*/
		while(curr[0]){
			/* creiamo un nuovo nodo (ul) */
			ul=dcr('ul');
			/* in base allo stato attuale del nodo il contenuto visibile o meno
				per ogni elemento ul contenuto si imposta la proprietà visible a none
			*/
			if(!curr.sts)ul.style.display="none";
			/* accoda all'ultimo elemento del puntatore il nuovo nodo */
			tree[tree.length-1].appendChild(ul);
			/* richiama ricorsivanente questa funzione al fine di ottenere un oggetto ramificato
				questa funzione ritorna sempre l'oggetto appena creato e quindi all'uscita il 
				seguente comando inserirà nell'oggetto HTML il nodo creato
			*/				
			ul.appendChild(this.makeTree(curr.shift()));
		}
		
		/* ritorna su di un livello di profondità riportando come auttuale l'elemento
			precedente e restituisce l'elemento al livello superiore
		*/
		url.pop();
		return tree.pop();
		
	}};

	obj.visiMan=function(obj){with(this){
		this.cli=obj.parentNode.parentNode.parentNode.parentNode;
		switch(cli.sts){
			case 1:
				for(s=1;s<cli.childNodes.length;s++)cli.childNodes[s].style.display="none";
				obj.src="imges/arrow_close.png";
				cli.sts=0;
				for(s=0;s<opn.length;s++){
					if(opn[s]==cli.url){
						opn=opn.slice(0,s).concat(opn.slice(s+1,opn.length));
						return;
					}
				}
			break;
			case 0:
				for(s=1;s<cli.childNodes.length;s++)cli.childNodes[s].style.display="block";
				obj.src="imges/arrow_open.png";
				cli.sts=1;
				/* Aggiunge la linea che memorizza lo stato aperto di questo nodo */
				for(s=0;s<opn.length;s++)if(opn[s]==cli.url)return;
				opn.push(cli.url);
			break;
		}
	}};
	
	/* trasforma l'oggetto creato nell'oggetto con il nome richiesto in id */
	eval(id+'=obj');delete obj;
}};