/* 
	Prepara l'ambiente ed esegue le funzioni di avvio delle librerie che ne fanno richiesta.
	
	stu		:	funzione di avvio
	stf		:	Array contenente il nome degli oggetti che richiedono la funzione di avvio 
*/
stu=new function(t,s){
	/* Crea l'array con le funzioni di avvio */
	this.stf={};
	/* funzione di partenza */
	this.str=function(){
		/* disabilita le funzioni standard HTML su pressione del tasto mouse*/
		document.onmousedown=function(){return false;};
		/* dati della configurazione di sistema */
		CONF=new Array();
		CONF.gridSnap=1;
		CONF.gridStep = 8;
		/* valuta le funzioni di avvio prenotate */
		for(var i in this.stf){eval(i).str()}

		t=document.getElementsByTagName('td');s=0;
		while(t[s]){
			if(t[s].getAttribute('class')=='rbt'){
				t[s].onmousedown=function(){this.className="rbs"};
				t[s].onmouseup=function(){this.className="rbt"}
			}
			s++;
		}
	}
};

/* funzioni per la gestione della griglia */
function showGrid(flag){
	grid.style.visibility=(flag==true ? 'visible' : 'hidden');
	gridSnap=(flag==true ? 1 : 0); 
};