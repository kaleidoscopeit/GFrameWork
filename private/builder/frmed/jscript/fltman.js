/*
	Funzioni che gestiscono l'oggetto/i usato per evidenziare e ridimensionare i webgets.
	In futuro verrà completata la possibilità di selezionare più webget contemporaneamente
	per spostarli o copiarli da altre parti.
	
	flt		:	oggetto principale per la gestione del floater
	fls		:	Matrice contenente tutti i floater creati
	add		:	Crea una nuova area di selezione e la ritorna alla funzione chiamante.
	dty		:	Elimina una o tutte le aree di selezione
*/
flt=new function(){
	/* istanzia alcune variabili interne */
	this.s='';this.ss='';this.div='';this.table='';
	this.fls=Array();stu.stf.flt=1;

	this.str=function(){this.obj=dge('flt');};
	
	
	/* aggiunge un area di selezione */
	this.add=function(){
		this.tmp=this.obj.cloneNode(true);
		edt.struct.root.en0.core.appendChild(this.tmp);
		this.tmp.style.visibility='visible';	
		return this.fls.push(this.tmp)-1;
	};

	/* rimuove una o più aree di selezione */
	this.dty=function(i){with(this){
		if(i=='all'){
			for(s=0;s<fls.length;s++){
				if(fls[s].parentNode)fls[s].parentNode.removeChild(fls[s]);			
			}
			fls=new Array();
			return;
		}
		fls[id].parentNode.removeChild(fls[id]);
	}}
};