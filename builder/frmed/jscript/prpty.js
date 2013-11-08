/*
	pty		:	oggetto principale per la gestione delle proprietà
	chn		:	canale dati per caricare le definizioni delle proprietà
	wow		:	scorciatoia a window.opener.wman
	wid		:	id della finestra chiamante
	cwb		:	webget corrente selezionato
	dcr		:	funzione abbreviata che crea un nuovo elemento
	dge		:	funzione abbreviata che attiene un elemento HTML
	upd		:	funzione di aggiornamento del contenuto
	str		:	funzione di avvio
	cvl		:	valore corrente della proprietà
	
	pta		:	area proprietà
	eva		:	area degli eventi
	ita		:	area delle interazioni
	rfa		:	area dei riferimenti
	ptb		:	pulsante per la selezione di pta
	evb		:	pulsante per la selezione di eva
	itb		:	pulsante per la selezione di ita
	rfb		:	pulsante per la selezione di rfa
*/

pty=new function(){with(this){
	this.str=function(){
		/* crea un canale dati per questa funzione */
		this.chn=new channel();
		chn.parent=this;
		
		/* Registra gli elementi HTML utili per l'interazione */
		this.pta=dge('ptya');
		this.eva=dge('evna');
		this.ita=dge('itca');
		this.rfa=dge('rfna');
		this.ptb=dge('ptyb');
		this.evb=dge('evnb');
		this.itb=dge('itcb');
		this.rfb=dge('rfnb');
		
		/* non mi ricordo a cosa serve e quindi la disabilito */
		/*window.onbeforeunload=function(){window.opener.childs.toolbox.pty=false;}*/

		/* gestisce gli eventi della tastiera */
		document.onkeydown=function(event){with(pty){
			/* esce se la finestra è stata cihusa */
			/*if(!this.wow.wnd[wid].editor)return false;*/
			/* se preumuto ctrl+s salva il documento */
			if(event.keyCode==83 && event.ctrlKey==true){				
				wow.wnd[wid].edt.spg();
				return false;
			}
			/* se premuto ctrl+a apre l'anteprima */
			if(event.keyCode==65 && event.ctrlKey==true){
				wow.wnd[wid].window.open('../../<?php echo $_SESSION['bld']['root']?>/?'+wow.wnd[wid].edt.struct.url.slice(0,wow.wnd[wid].edt.struct.url.length-4),'preview','location=no');
				return false;
			}
			/* se è selezionato un Webget e si preme canc lo elimina */ 
			if(event.keyCode==46&&!pty.lkd)wow.wnd[wid].wbt.rmv();
		}}
	};

	/* gestore delle schede visualizzate
		----------------------------------------------- */
	this.switcher=function(p,b){with(this){
		for(var i in {'pt':1,'ev':1,'it':1,'rf':1}){
			this[i+'a'].style.visibility='hidden';
			this[i+'b'].style.backgroundColor='#E5E5E5';
		}

		b.style.backgroundColor='lightgrey';
		this[p].style.visibility='visible'
	}};


	/* funzioni di acquisizione e aggiornamento del contenuto */
	
	/* aggiorna le proprietà visualizzate in base al webget corrente */
	this.upd=function(w){with(this){
		/* scorciatoia a window opener*/
		this.wow=window.opener.wman;
		/* id della finestra chiamante */
		this.wid=w;
		/* cattura il webget che ha richiamato l'evento */
		if(!(this.cwb=wow.wnd[wid].wbt.cwb))return;
		/* carica la definizione delle proprietà della libreria */
		chn.onload=function(d){this.parent.parse(d);};
		chn.url='./';
		chn.get['101']=null;
		chn.get.c=wow.wnd[wid].libs[cwb.box.TYPE].TAGSPCS.FAMILY;
		chn.get.l=cwb.box.TYPE;
		chn.send();
	}};


	/* funzione che popola le schede delle proprietà quando si seleziona un webget */
	this.parse=function(d){
		if(!(d=eval(d))){alert('Corrupted property data!');return};

		/* comincia creando il contenuto della tavola delle proprietà */
		this.maintable=dcr('table');
		this.text='',this.butt='',this.table='';this.tr='';this.td='';this.opt='';
		for(var s in d.properties){with(this){
			currField=d.properties[s].split(':');			
			this.cvl=eval('this.cwb.param.'+currField[4]);
			this.cvl=this.cvl?this.cvl:null;
			
			/* decide quale tipo di campo proprietà inserire */
			switch(currField[1]){
				case 'dialog' :
					/* crea la seconda parte del campo contenente un area di testo ed un pulsante */
					text=dcr('input');
					/*text.type='text';*/
					text.value=this.cvl?this.cvl:'';
					text.prpid=currField[4];
					text.style.cssText="width:100%;border:1px solid grey;font:12px arial,Sans;";
					text.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
						/* aggiorna il toolbox dell'albero dei webget, se presente */
						window.opener.wman.cfnc('dct.up();','','topan');
					};

					butt=dcr('input');
					with(butt){
						type='button';
						value='...';
						style.cssText="font-size:7px;height:17px;width:5px; ";
					   butt.chain=text;
					   butt.url=currField[2];
					   butt.title="Apri la finestra di modifica.";
						butt.onclick = function onClick(){
							pty.cfld=this.chain;
							rfw.wman.odl('101&f=d&'+this.url,'topan');
					}}
			
					table=dcr('table');
					tr=dcr('tr');
					table.appendChild(tr);
					td = dcr('td');
					td.width='100%';
					tr.appendChild(td);
					td.appendChild(text);
					td=dcr('td');
					td.vAlign='bottom';
					tr.appendChild(td);
					td.appendChild(butt);
				break;
				case 'select' :
					text = dcr('select');
					text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
					text.prpid=currField[4]; 
					text.onchange = function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
					};
					text.opl=currField[2].split(',');
					text.opv=currField[3].split(',');
					while(text.opl[0]){
						text.temp=dcr('option');
						text.temp.value=(text.opv[0]?text.opv.shift():text.opl[0]);						
						text.temp.text=text.opl.shift();
						text.add(text.temp,null);
					};
					text.value=this.cvl;
					
					table=dcr('table');
					tr=dcr('tr');
					table.appendChild(tr);
					td = dcr('td');
					td.width='100%';
					tr.appendChild(td);
					td.appendChild(text);
				break;
				case 'text' :
					text=dcr('input');
					text.type='text';
					text.value=this.cvl;
					text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
					text.prpid=currField[4]; 
					text.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
					};
					
					text.onfocus=function onFocus(){pty.lkd=1};
					text.onblur=function onBLur(){pty.lkd=0};
					
					table=dcr('table');
					tr=dcr('tr');
					table.appendChild(tr);
					table.width='100%';
					td = dcr('td');
					td.width='100%';
					tr.appendChild(td);
					td.appendChild(text);
				break;
				case 'spin' :
					text=dcr('input');
					text.type='text';
					text.value=(currField[2]!=''?currField[2]:this.cvl);
					text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
					text.prpid=currField[4]; 
					text.onchange = function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
					};
					// enable wheel event
					text.onfocus=function onFocus(){
						this.addEventListener('DOMMouseScroll', wheel, false);
					};
					
					text.onblur=function onBLur(){
						this.removeEventListener('DOMMouseScroll', wheel, false);
					};
					butt=dcr('button');
					butt.title="Aumenta valore";
					butt.style.cssText="width:10px;height:10px;";
					butt.chain=text;
					butt.onclick=function onClick(){chSpin(this.chain,'up')};

					table=dcr('table');
					table.cellSpacing='0';
					table.cellPadding='0';
					tr=dcr('tr');
					table.appendChild(tr);
					td=dcr('td');
					tr.appendChild(td);
					td.appendChild(butt);
					
					butt=dcr('button');
					butt.title="Diminuisci valore";
					butt.style.cssText="width:10px;height:10px;";
					butt.chain=text;
					butt.onclick = function onClick(){chSpin(this.chain,'down')};
					
					tr=dcr('tr');
					table.appendChild(tr);
					td=dcr('td');
					tr.appendChild(td);
					td.appendChild(butt);
					
					td=dcr('td');
					td.width='100%';
					td.appendChild(text);
					tr=dcr('tr');
					tr.appendChild(td);
					td = dcr('td');
					td.appendChild(table);
					tr.appendChild(td);
					table=dcr('table');					
					table.appendChild(tr);
				break;
				case 'bool':
					opt=dcr('input');
					opt.type='checkbox';
					opt.checked=eval(this.cvl);
					opt.prpid=currField[4];
					opt.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,(this.checked==true?'1':''));
					};
					
					td=dcr('td');
					td.appendChild(opt);
					tr=dcr('tr');
					tr.appendChild(td);
										
					td=dcr('td');
					tr.appendChild(td);
					table=dcr('table');
					table.appendChild(tr);
				break;
			}

			tr=dcr('tr');
			maintable.appendChild(tr);
			maintable.cellPadding='0';
			maintable.cellSpacing='0';

			td = dcr('td');tr.appendChild(td);
			td.noWrap=1;td.style.cssText="font:  12px arial,Sans;";			
			/* esegue solo nei casi non speciali come : space, line, head*/
			if(currField[1]=='head'){
				td.colSpan='2';
				td.style.borderBottom='2px solid';
				td.style.paddingTop='5px';				
				td.align='center';
				td.innerHTML=currField[0];
			} else {
				td.innerHTML=currField[0]+' :&nbsp;&nbsp;';
				td=dcr('td');tr.appendChild(td);
				td.width='100%';td.noWrap=1;
				td.appendChild(table);
			}
		}}	

		this.pta.removeChild(this.pta.firstChild);
		this.pta.appendChild(this.maintable);		
		
		
		/* prosegue creando il contenuto della tavola degli eventi */
		this.maintable = dcr('table');this.text='',this.butt='',this.table='';this.tr='';this.td='';

		for(var s in d.events){
			currField=d.events[s].split(':');
			this.cvl=eval('this.cwb.param.'+currField[2]);
			this.cvl=this.cvl?this.cvl:null;
			
			this.text=dcr('textarea');
			/*this.text.type='text';*/
			this.text.innerHTML=this.cvl;
			this.text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
			this.text.prpid=currField[2]; 
			this.text.onChange=function onChange(){
				pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
			};

			this.butt=dcr('input');
			this.butt.type='button';
			this.butt.value='...';
			this.butt.style.cssText="font-size: 7px; height: 15px; width: 5px; ";
			this.butt.chain=this.text;
			this.butt.title="Apri la finestra di modifica.";
			
			this.table=dcr('table');
			this.tr=dcr('tr');
			this.table.appendChild(this.tr);
			this.td=dcr('td');
			this.td.width='100%';
			this.tr.appendChild(this.td);
			this.td.appendChild(this.text);
			this.td=dcr('td');
			this.td.vAlign='bottom';
			this.tr.appendChild(this.td);
			this.td.appendChild(this.butt);

			this.tr=dcr('tr');
			
			this.td=dcr('td');
			this.td.noWrap=1;
			this.td.style.cssText="font:  12px arial,Sans;";	
			this.td.innerHTML=currField[0]+' :&nbsp;&nbsp;';
			this.tr.appendChild(this.td);

			this.td=dcr('td');
			this.td.width='100%';
			this.td.noWrap=1;
			this.td.appendChild(this.table);
			this.tr.appendChild(this.td);
			
			this.maintable.appendChild(this.tr);
			this.maintable.border='0';
			this.maintable.cellPadding='0';
			this.maintable.cellSpacing='0';
			
			switch(currField[1]){
				case 'jscode' :
					this.butt.onclick=function onClick(){
						pty.cfld=this.chain;
						rfw.wman.odl('14&f=d&'+this.url,'topan','width=500,height=400');
						//window.opener.wman.ots('14','jsedit','forms','width=500,height=400');
					};
				break;
				case 'phpcode' :
				case 'jscode' :
					this.butt.onclick=function onClick(){
						pty.cfld=this.chain;
						rfw.wman.odl('15&f=d&'+this.url,'topan','width=500,height=400');
						//window.opener.wman.ots('15','phpedit','forms','width=500,height=400');
					};
				break;
			}
		}	


		this.eva.removeChild(this.eva.firstChild);
		this.eva.appendChild(this.maintable);
		
		
		/* continua creando il contenuto della tavola delle interazioni */
		this.maintable=dcr('table');
		this.text='',this.butt='',this.table='';this.tr='';this.td='';this.opt='';
		for(var s in d.interactions){
		with(this){
			currField = d.interactions[s].split(':');
			this.cvl=this.cvl?this.cvl:null;

			/* decide quale tipo di campo proprietà inserire */
			switch(currField[1]){
				case 'select' :
					text=dcr('select');
					text.value=currField[2];
					text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
					text.prpid=currField[4]; 
					text.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
					};
					text.opt=currField[2].split(',');
					while(text.opt[0]){
						text.temp=dcr('option');
						text.temp.value=text.opt[0];
						text.temp.text=text.opt.shift();
						text.add(text.temp,null);
					};
					
					table=dcr('table');
					tr=dcr('tr');
					table.appendChild(tr);
					td=dcr('td');
					td.width='100%';
					tr.appendChild(td);
					td.appendChild(text);
				break;
				case 'spin' :
					text=dcr('input');
					text.type='text';
					text.value=currField[2];
					text.style.cssText="width:100%;border:1px solid grey;font: 12px arial,Sans;";
					text.prpid=currField[4]; 
					text.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.value);
					};	

					butt=dcr('input');
					butt.type='button';
					butt.value='^';
					butt.style.cssText="font-size: 3px;width:5px;height:8px;";
					butt.chain=text;
					butt.onclick=function onClick(){chSpin(this.chain,'up')};

					table=dcr('table');
					table.border='0';
					table.cellSpacing='0';
					table.cellPadding='0';
					tr=dcr('tr');
					table.appendChild(tr);
					td = dcr('td');
					tr.appendChild(td);
					td.appendChild(butt);
					
					butt=dcr('input');
					butt.type='button';
					butt.value='^';
					butt.style.cssText="font-size: 3px;width:5px;height:8px;";
					butt.chain=text;
					butt.onclick=function onClick(){chSpin(this.chain,'down')};			
					
					tr=dcr('tr');
					table.appendChild(tr);
					td=dcr('td');
					tr.appendChild(td);
					td.appendChild(butt);
					
					td=dcr('td');
					td.width='100%';
					td.appendChild(text);
					tr=dcr('tr');
					tr.appendChild(td);
					td=dcr('td');
					td.appendChild(table);
					tr.appendChild(td);
					table=dcr('table');					
					table.appendChild(tr);
				break;
				case 'bool':
					opt=dcr('input');
					opt.type='checkbox';
					opt.checked=this.cvl;
					opt.prpid=currField[4];
					opt.onchange=function onChange(){
						pty.wow.wnd[wid].wbt.upy(this.prpid,this.checked);
					};
					
					td=dcr('td');
					td.appendChild(opt);
					tr=dcr('tr');
					tr.appendChild(td);
										
					td=dcr('td');
					tr.appendChild(td);
					table=dcr('table');
					table.appendChild(tr);
				break;
				case 'button':
					butt=dcr('input');butt.type='button';
					butt.style.width='100%';
					butt.value='>>';
					butt.prpid=currField[4];
					butt.quest=currField[2];
					butt.onclick=function onClick(){
						/* se quest è impostato richiede un ulteriore conferma*/
						if(this.quest)if(confirm(this.quest))pty.wow.wnd[wid].wbt.upy(this.prpid,'');
						else wbget_updpty(this.prpid,'');
					};
					
					td=dcr('td');td.style.width='100%';
					td.appendChild(butt);
					tr=dcr('tr');
					tr.appendChild(td);
										
					td=dcr('td');
					tr.appendChild(td);
					table=dcr('table');
					table.appendChild(tr);
				break;
			}

			tr=dcr('tr');
			maintable.appendChild(tr);
			maintable.border='0';
			maintable.cellPadding='0';
			maintable.cellSpacing='0';

			td=dcr('td');tr.appendChild(td);
			td.noWrap=1;td.style.cssText="font:  12px arial,Sans;";			
			/* esegue solo nei casi non speciali come : space, line, head*/
			if(currField[1]=='head'){
				td.colSpan='2';
				td.style.borderBottom='2px solid';
				td.style.paddingTop='5px';				
				td.align='center';
				td.innerHTML=currField[0];
			} else {
				td.innerHTML=currField[0]+' :&nbsp;&nbsp;';
				td=dcr('td');tr.appendChild(td);
				td.width='100%';td.noWrap=1;
				td.appendChild(table);
			}
		}}	

		this.ita.removeChild(this.ita.firstChild);
		this.ita.appendChild(this.maintable);		
	}
}};	
	/* funzione che apre una piccola pagina contenente una lista di possibili valori per mselect
	 funzione */
	
	/* funzione che apre una piccola pagina contenente un selettore per files
		funzione */
	
/* funzione che incrementa o decrementa il valore nel campo spin mantenendo il suffisso */
function chSpin(field,dir){
	switch(dir){
		case 'up' :
			field.value.search('px')!='-1'?field.value=(field.value.slice(0,field.value.search('px'))-1+2)+'px':null;  
			field.value.search('%')!='-1'?field.value=(field.value.slice(0,field.value.search('%'))-1+2)+'px':null; 
			value=field.value-1+2;if(!isNaN(value))field.value=value;
		break;
		
		case 'down' :
			field.value.search('px')!='-1'?field.value =(field.value.slice(0,field.value.search('px'))-1)+'px':null;  
			field.value.search('%')!='-1'?field.value =(field.value.slice(0,field.value.search('%'))-1)+'px':null; 
			value=field.value-1;if(!isNaN(value))field.value=value;
		break;
	};
	field.onchange();
};

function wheel(e){
	chSpin(this,(e.detail<0?'up':'down'));
 	if (e.preventDefault)e.preventDefault();
	e.returnValue=false;
}