layman=new function(){
	this.startPan=function(or,e,A,B){
	if(!e)e=window.event;
	/* fissa i valori iniziali in cui si trovano le due aree ed il mouse nel momento del click */
	with(this){
		this.PA=document.getElementById(A);
		this.PB=document.getElementById(B);
		this.CX=e.clientX;
		this.CY=e.clientY;
	/* in base al tipo di scorrimento richiesto fissa le dimensioni rispettivamente o orrizzontali
	 o verticali delle due aree. Poi abilita l'evento appripriato sul movimento del mouse.*/
		switch(or){
			case 'V' :
				this.PAH=PA.offsetHeight;
				this.PBH=PB.offsetHeight;
				this.PTH=PAH+PBH;
				document.body.onmousemove=function(event){layman.VPan(event);return false;};
			break;
		
			case 'H' :
				this.PAW=PA.offsetWidth;
				this.PBW=PB.offsetWidth;
				this.PTW =PAW+PBW;
				document.body.onmousemove=function(event){layman.HPan(event);return false;};
			break;
		}
		
		/* quando il pulsante del mouse viene rilasciato torna annulla tutti gli eventi */
		document.body.onmouseup=function(){this.onmousemove=null;};
	}};
	
	/* funzione che calcola ed imposta le dimensioni verticali delle due aree secondo
	 lo spostamento del mouse*/
	this.VPan=function(e){
 if(!e)e=window.event;
	with(this){
		/* nuove dimensioni verticali */
		this.AH=PAH-0+(e.clientY-CY);
		this.BH=PBH-0-(e.clientY-CY);
		/* limita lo scorrimento secondo i limiti delle aree */
		AH>PTH?AH=PTH:null;
		AH<0?AH='0':null;
		BH>PTH?BH=PTH:null;
		BH<0?BH='0':null;
		/* imposta realmente le nuove dimensioni */
 		PA.style.height = AH+'px';
		PB.style.height = BH+'px';
	}};

	/* funzione che calcola ed imposta le dimensioni orrizzontali delle due aree secondo
	 lo spostamento del mouse */
	this.HPan=function(e){
 if(!e)e=window.event;
	with(this){
		this.AW = PAW-0+e.clientX-CX;
		this.BW = PBW-0-(e.clientX-CX);	
		AW >PTW ? AW = PTW : null;
		AW <0 ? AW = '0' : null;	
		BW >PTW ? BW = PTW : null;
		BW <0 ? BW = '0' : null;	 
		PA.style.width = AW+'px';
		PB.style.width = BW+'px';
	}};
};
