$_.lib.pack_hpane={
	// setup some variables
	c:[],a:['misz','masz','lkd'],f:['onclick'],	
 	// funzione standard di avvio
	construct:function(t,s,ss){with(this){
		// ottiene dall'HTML i tag speciali AJFORM
		t=document.getElementsByTagName('div');

		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='pack:hpane'){
				t[s].style.cssText+=";position:absolute;height:100%;top:0px;overflow:hidden;";
				for(ss in a)t[s][a[ss]]=t[s].getAttribute(a[ss]);
				// grab this webget by id in the global context (FF only)
				try{eval ('var '+t[s].id+'=t[s];')}catch(e){};
				// memorizza localmente un riferimento al nuovo oggetto creato in un array
				c.push(t[s]);
				// costruisce le parti rimanenti dell'oggetto
				build(t[s]);
			}
		}}
	},
	
	// starts per-object interaction script (flush)
	flush:function(s,c){c=this.c;for(s in c)c[s].fs()},
	
	// builds javascript object
	build:function(n){with(n){
		n.fs=function(s){with(this){
		}};
	}}
};