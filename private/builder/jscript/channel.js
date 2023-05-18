function channel(){
	this.sock=dcr('iframe');
	this.sock.style.cssText='width:00px;height:00px;border-width:0px;';
	this.sock.url='about:blank';
	this.sock.pr=this;	
	this.sock.onload=function(){
		this.vb==true?alert(this.contentWindow.document.body.innerHTML) : null;
		this.pr.onload(this.contentWindow.document.body.textContent);
	};

	this.get={};this.post={};
	document.body.insertBefore(this.sock,document.body.firstChild);

	this.onload=function(data){};
	
	/* invia la richiesta */
	/* È possibile specificare gli argomenti per la richiesta preparando una o entrambe
		 le variabili get e post sotti forma di oggetti javascript */
	this.send=function(){
		if(!this.url){alert('no url');return;}
  		/* crea il nuovo URL formanto anche dai parametri nell'oggetto get */
  		/* Ho inserito una opzione che non mette il valore del parametro get se
  		   uesto non è passato per mantenere coerenza con alcuni tipi di chiamata */
 		this.frl=this.url+'?';
 		for(var i in this.get)this.frl+=i+(this.get[i]?'='+this.get[i]:'')+'&';

		/* Per ogni dato post crea una casella di testo nel form con i caratteri speciali 
			convertiti già in codici URL questo previene eventuali problemi durante il trasporto.
			I dati post vengono considerati come quello che si vuole realmente trasferire, quindi
			i caratteri speciali vengono tradotti per consentirne il trasporto. Non è possibile
			inserire codici speciali manualmente ed aspettarsi che vengano trattati come tali*/
  		for (var i in  this.post){
  			this.post[i]=this.post[i].replace(/%/g,'%25');
			this.post[i]=this.post[i].replace(/&/g,'%26');
 			this.post[i]=this.post[i].replace(/\"/g,'%22');/*"*/
  			this.post[i]=this.post[i].replace(/\'/g,'%27');/*'*/
			this.post[i]=this.post[i].replace(/\\/g,'%5C');
			this.post[i]=this.post[i].replace(/\+/g,'%2B');

  			/* queste due sostituzioni non dovrebbero servire */
   		/*this.post[i]=this.post[i].replace(/</g,"&#60;");*/
  			/*this.post[i]=this.curr.replace(/>/g,"%3E");*/
  			this.frm += '<textarea name="'+i+'" >'+this.post[i]+'</textarea>;'/*'*/
 		};


		/* se sono stati creati dati post viene creato il form */
		if(this.frm){
			this.frm='<html><head><meta http-equiv="Content-Type" content="text/plain; charset=utf-8"></head><body>'+
									'<form method="post" action="'+this.frl+'">'+this.frm+'<INPUT type="submit" ></form></body>';

			this.sock.contentWindow.document.write(this.frm);
			this.sock.contentWindow.document.forms[0].submit();

 		} else this.sock.src=this.frl;

 		/* resetta i valori utilizzati per questa richiesta in modo da non accavallarsi con richieste successive */
		this.frm=null;this.url=null;this.get={};this.post={};
	}
};