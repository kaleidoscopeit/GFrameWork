/*
	Estensione dell'oggetto per la gestione dei webget che manipola l'ordine
	della pila degli stessi.
	
	wbt		:	oggetto principale per la gestione dei webget
	mvu		:	porta il webget selezionato uno strato più in alto. !Nella menù ad albero dei
					webget quello selezionato si sposterà in basso
	swp		:	oggetto temporaneo usato per lo swapping
*/

/* porta il wbt attuale sopra di un livello */
wbt.mvu=function(){with(this){
	/* cattura il wbt successivo */
	this.twb=gne();
	/* esce dalla funzione con un avviso nel caso in cui sia l'ultimo wbt della pila */
	if(twb==false){alert("<?php echo $lc_msg['frmed_wbtstk_0'];?>");return false;}
	/* scambia fra loro gli oggetti reali HTML */
	cwb.box.parentNode.insertBefore(twb.box,cwb.box);
	/* swappa gli elementi all'interno dell'albero	 */
	this.swp=cwb;
	for(var s in cwb.prn){
		if(cwb.prn[s]==cwb)this.cdx=s;
		if(cwb.prn[s]==twb)this.tdx=s;
	}

	cwb.prn[cdx]=cwb.prn[tdx];
	cwb.prn[tdx]=swp;

	window.opener.wman.cfnc('dct.up();','','topan');
}};

/* porta il wbt attuale sopra di un livello */
wbt.mvd=function(){with(this){
	/* cattura il wbt successivo */
	this.twb=gpr();
	/* esce dalla funzione con un avviso nel caso in cui sia l'ultimo wbt della pila */
	if(twb==false){alert("<?php echo $lc_msg['frmed_wbtstk_1'];?>");return false;}
	/* scambia fra loro gli oggetti reali HTML */
	cwb.box.parentNode.insertBefore(cwb.box,twb.box);
	/* swappa gli elementi all'interno dell'albero	 */
	this.swp=cwb;
	for(var s in cwb.prn){
		if(cwb.prn[s]==cwb)this.cdx=s;
		if(cwb.prn[s]==twb)this.tdx=s;
	}

	cwb.prn[cdx]=cwb.prn[tdx];
	cwb.prn[tdx]=swp;

	/* aggiorna l'albero dei webgets*/
	window.opener.wman.cfnc('dct.up();','','topan');
}};

/* porta il wbt attuale sopra di un livello */
wbt.mvb=function(){with(this){
	/* imposta il puntatore al wbt successivo come quello attuale */
	this.twb=cwb;
	/* partendo da quello attuale sposta ricorsivamente il webget in basso */
	while(gpr(twb)!=false){
		twb=gpr(twb);
		/* scambia fra loro gli oggetti reali HTML */
		cwb.box.parentNode.insertBefore(cwb.box,twb.box);
		/* swappa gli elementi all'interno dell'albero	 */
		this.swp=cwb;
		for(var s in cwb.prn){
			if(cwb.prn[s]==cwb)this.cdx=s;
			if(cwb.prn[s]==twb)this.tdx=s;
		}
	
		cwb.prn[cdx]=cwb.prn[tdx];
		cwb.prn[tdx]=swp;
	}
	/* aggiorna l'albero dei webgets*/
	window.opener.wman.cfnc('dct.up();','','topan');		
}};

/* porta il wbt attuale sopra di un livello */
wbt.mvt=function(){with(this){
	/* imposta il puntatore al wbt successivo come quello attuale */
	this.twb=cwb;
	/* partendo da quello attuale sposta ricorsivamente il webget in alto */
	while(gne(twb)!=false){
		twb=gne(twb);
		/* scambia fra loro gli oggetti reali HTML */
		cwb.box.parentNode.insertBefore(twb.box,cwb.box);
		/* swappa gli elementi all'interno dell'albero	 */
		this.swp=cwb;
		for(var s in cwb.prn){
			if(cwb.prn[s]==cwb)this.cdx=s;
			if(cwb.prn[s]==twb)this.tdx=s;
		}

		cwb.prn[cdx]=cwb.prn[tdx];
		cwb.prn[tdx]=swp;
	}
	/* aggiorna l'albero dei webgets*/
	window.opener.wman.cfnc('dct.up();','','topan');
}};