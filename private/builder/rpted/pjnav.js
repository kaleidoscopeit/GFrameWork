startupFunct.pjnav = function(){
	OBJS.pjnav=document.getElementById('pjnav');
	OBJS.pjnavhl = document.getElementById('pjnavhl');
	VARS.count=-1;
	STUF.tree = new Array;STUF.url = new Array;
	STUF.cont=CrEl('ul');
	STUF.cont.appendChild(pjnav_buildTree(PJTREE));
	STUF.cont.style.cssText = "margin-left:-20px;margin-top:5px;position:absolute;";
	OBJS.pjnav.appendChild(STUF.cont);


}



function pjnav_buildTree(curr){
	STUF.li = CrEl('li');
	STUF.li.style.cssText="list-style-type:none;margin-left:-20px;margin-top:-5px;";

	STUF.table = CrEl('table');

	STUF.table.onclick = function onClick(){OBJS.pjnavhl.style.top=this.offsetTop+6+"px";OBJS.pjnavhl.style.height=this.offsetHeight-4+ "px";return false;}
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
	STUF.td.appendChild(STUF.img);
	
	STUF.td = CrEl('td');
	STUF.td.style.fontSize = "12px";
	STUF.td.innerHTML=curr.tgt;
	STUF.tr.appendChild(STUF.td);

	// identifica il tipo di file e disegna l'icona opportuna
	switch(curr.tgt.slice(-3)){
		case "xml" :
		case "ml~" :
			STUF.img.src="imges/form16.png";
			STUF.arrw.src="imges/dot7.png";
			STUF.table.ondblclick = function onDblClick(){pject_loadPage(this.loct);return false;};
		break;
		case "rpt" :
			STUF.img.src="imges/document16.png";
			STUF.arrw.src="imges/dot7.png";
		break;
		default :
			STUF.img.src="imges/folder16.png";
			STUF.arrw.src="imges/arrow_close.png";
			STUF.arrw.onclick = function onClick(){pjnav_visiMan(this);return false;};
			
	}
	
	// imposta l'elemento HTML appena creato come quello attuale in fondo alla colonna	
	STUF.tree.push(STUF.li);VARS.count++;
	// accoda il testo in target per ricostruire l'URL
	STUF.url.push(curr.tgt);
	// imposta l'URL del nodo corrente come variabile interna allo stesso
	STUF.table.loct = STUF.url.join('/');

	// analizza il contenuto per verificare se esistono altri elementi nidificati			
	for(var sub in curr){
		// se il contenuto è un contenitore procede con l'analisi
		if(isNaN(sub)!=1){
			// converte l'immagine attuale a quella di cartella

			// richiama ricorsimanente questa funzione al fine di ottenere un oggetto ramificato
			STUF.ul = CrEl('ul');
			STUF.ul.style.display="none";
			STUF.tree[VARS.count].appendChild(STUF.ul);
			STUF.ul.appendChild(pjnav_buildTree(curr[sub]));
		}
	}

	// ritorna su di un livello di profondità riportando come auttuale l'elemento precedente e restituisce l'elemento al livello superiore
	VARS.count--;
	STUF.url.pop();
	return STUF.tree.pop();
}

function pjnav_visiMan(obj){
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
