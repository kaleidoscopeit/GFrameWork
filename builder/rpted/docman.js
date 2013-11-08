startupFunct.docman = function(){
	OBJS.docnavhl = document.getElementById('docnavhl');
}

// funzioni che gestiscono il caricamento ed il salvataggio dei documenti
// -----------------------------------------------------------------------------------------------------
//
// carica il form specificato
function pject_loadPage(src){
	wkspc_addNew(src);
	callURL('xjparser.php?file='+src,'pject_buildPage');
}

// scansiona l'albero e per ogni ramo richiede l'aggiunta del webget specifico
function pject_buildPage(obj,tree){

	if(!tree){obj = obj[1];tree = new Array('root');}

	// azzera la variabile properties per inserirvi quelle dell'oggetto attuale
	this.properties = new Array();
	// ricerca le proprietà e le acquisisce
	for(var sub in obj){
		if(obj[sub].length!=null){
			this.properties[sub] = obj[sub];
		}
	}

	this.wenum = docmt_addWebget(this.properties.TAGTYPE,properties,tree.join('/'));

	// ricerca se sono presenti webgets nidificati e lancia ricorsivamente il parser
	tree.push(this.wenum);
	for(var sub in obj){
		if(obj[sub].length==null){pject_buildPage(obj[sub],tree);}
	};
	tree.pop();
	if(tree.length=='1'){docmt_updateTree();wkspc_switch(VARS.areaEnum-1);};
}

// ricostruisce il codice xml
function pject_buildXML(curr){
	if(!curr){curr=currArea['root']['en0'];this.out='';this.tab='';}
	
	this.out += this.tab+'<'+curr.properties.TAGTYPE+' ';
	for(var psub in curr.properties){
		psub!= 'TAGNUM' && psub!= 'TAGTYPE' && psub!= 'TAGFAMILY' ? this.out += psub+'="'+curr.properties[psub]+'" ' : null;
	}	
	
	this.out += '>\n';
	for(var sub in curr){
		if(sub.indexOf('en')>-1 && curr[sub]!=null){
			this.tab +='	';
			pject_buildXML(curr[sub]);
			this.tab = this.tab.slice(0,this.tab.length-1);
		}
	}

	this.out += this.tab+'</'+curr.properties.TAGTYPE+'>\n';
	
	return this.out;
	
}

// funzioni per la manipolazione del tipo di documento e l'inserimento o eliminazione dei webget contenuti
// ------------------------------------------------------------------------------------------------------------------------------------------------------
//
//
// aggiunge alla posizione indicata un nuovo webget ritorna il numero assegnato
// (l'oggetto è costituito da un involucro 'box' e da uno spazio per inserire elementi figlio 'core')
function docmt_addWebget(type,defaults,vtree){
	// ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root
	this.tree = vtree.split('/');
 	this.tree = this.tree.join('.en');

	// ottiene il numero degli oggetti contenuti per decidere quale numero assegnare al nuovo oggetto
	this.current = currArea.eval(this.tree);
	this.enum = 0;
	for(var sub in this.current){
		if(sub.slice(0,2)=='en'){
			this.enum ++;
		}
	};

	// se la posizione del nuovo webget è in radice, allora si adotta l'unico indice possibile e obbligato
	if(this.tree=='root')this.enum=0;
	
	// usa il codice di creazione della libreria specificate per ottenere l'oggetto da appendere
	// gli invia le informazioni sulla posizione attuale 
	this.webget = libs.eval(type).create(vtree+'/'+this.enum,defaults);

	// se il valore ritornato dalla fnzione di creazione è -1 valuta l'errore inviando un messaggio
	// e restituisce -1 ad un eventuale funzione chamante	
	if(this.webget==-1){alert('Non è stato possibile aggiungere il webget alla posizione indicata!');return -1;}
	
	// appende all'albero l'oggetto ottenuto
	this.current['en'+this.enum] = this.webget;
	this.current.core.appendChild(this.current['en'+this.enum].box);
	// aggiorna la visualizzazione dell'albero
	docmt_updateTree();	
	// ritorna il numero assegnato al webget corrente
	return this.enum;
}

function docmt_remWebget(vtree){
	// identifica la posizione dell'ogetto corrente, passato esplicitamente o no
	if(vtree){
		// ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root
		STUF.tree = vtree.split('/');
		STUF.curen = STUF.tree.pop();
 		STUF.tree = STUF.tree.join('.en');
		// ottiene l'elemento ed il suo contenitore
		STUF.curel = currArea.eval(STUF.tree+'.en'+STUF.curen);
		STUF.parel = currArea.eval(STUF.tree);
	} else {
		// ottiene l'elemento
		STUF.curel=currItem;
		// ricostruisce in base alla stringa tree la posizione all'interno dell'oggetto root
		STUF.tree = STUF.curel.box.vtree.split('/');
		STUF.curen = STUF.tree.pop();
 		STUF.tree = STUF.tree.join('.en');
 		// ottiene il contenitore
 		STUF.parel = currArea.eval(STUF.tree);
	}
	
	if(confirm("L'azione rimuovera' il webget definitivamente.")==1){
		// rimuove l'oggetto HTML dallo spazio di lavoro
		STUF.curel.box.parentNode.removeChild(STUF.curel.box);
		// annulla il riferimento dell'enumeratore nell'albero della pagina
		STUF.parel['en'+STUF.curen]=null;
		// aggiorna la visualizzazione dell'albero
		docmt_updateTree();
		// annulla l'evento su pressione
		document.onkeypress = ''
	}
}




// genera l'HTML dell'albero corrispondente alla struttura del documento
function docmt_updateTree(){
	if(!currArea.root.en0){OBJS.docstrct.removeChild(OBJS.docstrct.lastChild);return false;}
	VARS.count=-1;
	STUF.tree = new Array;STUF.url = new Array;
	STUF.cont=CrEl('ul');
	STUF.cont.appendChild(docmt_buildTree(currArea.root.en0));
	STUF.cont.style.cssText = "margin-left:-20px;margin-top:5px;position:absolute;";
	OBJS.docstrct.removeChild(OBJS.docstrct.lastChild);
	OBJS.docstrct.appendChild(STUF.cont);
}


function docmt_buildTree(curr){
	STUF.li = CrEl('li');
	STUF.li.style.cssText="list-style-type:none;margin-left:-20px;margin-top:-5px;";

	STUF.table = CrEl('table');
	STUF.table.ondblclick = function onDblClick(){alert(this.vtree);};
	// imposta l'URL del nodo corrente come variabile interna allo stesso
	STUF.table.vtree = curr.box.vtree;
	
	STUF.table.ondblclick = function onDblClick(){wbget_select(this.vtree);OBJS.docnavhl.style.top=this.offsetTop+6+"px";OBJS.docnavhl.style.height=this.offsetHeight-4+ "px";return false;}
	STUF.li.appendChild(STUF.table);
	
	STUF.tr = CrEl('tr');
	STUF.table.appendChild(STUF.tr);
	
	STUF.td = CrEl('td');
	STUF.td.vAlign="bottom";
	STUF.td.style.cssText ="width:20px;";
	STUF.td.noWrap=1;	
	STUF.tr.appendChild(STUF.td);
	
	STUF.arrw = CrEl('img');
	STUF.arrw.border="0";
	STUF.arrw.status = "hid";
	STUF.td.appendChild(STUF.arrw);

	STUF.img = CrEl('img');
	STUF.img.border="0";
	STUF.img.width="16";
	STUF.td.appendChild(STUF.img);
	
	STUF.td = CrEl('td');
	STUF.td.style.fontSize = "12px";
	STUF.td.innerHTML=curr.properties.TAGTYPE;
	STUF.tr.appendChild(STUF.td);

	STUF.img.src= "dev-lib/"+curr.properties.TAGFAMILY+"/"+curr.properties.TAGTYPE+"/icon.png";
	STUF.arrw.src="imges/dot7.png";

	// imposta l'elemento HTML appena creato come quello attuale in fondo alla colonna	
	STUF.tree.push(STUF.li);VARS.count++;
	// accoda il testo in target per ricostruire l'URL

	// analizza il contenuto per verificare se esistono altri elementi nidificati			
	for(var sub in curr){

		// se il contenuto è un contenitore procede con l'analisi
		if(sub.slice(0,2)=='en' && curr[sub]!=null){
			// converte il marcatore di lista in apribile
			STUF.arrw.src="imges/arrow_close.png";
			STUF.arrw.onclick = function onClick(){docmt_visiMan(this);return false;};
			
			// richiama ricorsimanente questa funzione al fine di ottenere un oggetto ramificato
			STUF.ul = CrEl('ul');
			STUF.ul.style.display="none";
			STUF.tree[VARS.count].appendChild(STUF.ul);
			STUF.ul.appendChild(docmt_buildTree(curr[sub]));
			
			// distrugge il marcatore attuale per bloccare le sovrapposizioni
			STUF.arrw = {};
		}
	}

	// ritorna su di un livello di profondità riportando come auttuale l'elemento precedente e restituisce l'elemento al livello superiore
	VARS.count--;
	STUF.url.pop();
	return STUF.tree.pop();
}

function docmt_visiMan(obj){
	OBJS.curr = obj.parentNode.parentNode.parentNode.parentNode;
//	alert(OBJS.curr.childNodes.length);//dnkcfhasdkjbkcbadjkbcasbcbasklbxclascbklaxskl
	switch(obj.status){
		case "vis":
			for(STUF.x=1;STUF.x<OBJS.curr.childNodes.length;STUF.x++){
				OBJS.curr.childNodes[STUF.x].style.display = "none";
			}
			obj.src ="imges/arrow_close.png";
			obj.status = "hid";
		break;
		case "hid":
			for(STUF.x=1;STUF.x<OBJS.curr.childNodes.length;STUF.x++){
				OBJS.curr.childNodes[STUF.x].style.display = "block";
			}
			obj.src ="imges/arrow_open.png";
			obj.status = "vis";
		break;
	}
}