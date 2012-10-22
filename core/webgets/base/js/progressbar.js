$_.lib.base_progressbar={
	// setup some variables
	c:[],a:['ornt'],f:[],
	// funzione standard di avvio
	construct:function(t,s,ss){with(this){
		// ottiene dall'HTML i tag speciali AJFORM
		t=document.getElementsByTagName('div');

		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='base:progressbar'){
				for(ss in a)t[s][a[ss]]=t[s].getAttribute(a[ss]);
				for(ss in f)eval('t[s][f[ss]]=function(){'+t[s].getAttribute(f[ss])+'};');
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
	build:function(n){

		n.fs=function(s){
			this.bar=this.firstChild;
		}
		
		n.setProgress=function(p){

			switch(n.ornt){
				case 'LR':
					n.bar.style.width=p+"%";
					break;
				case 'RL':
					n.bar.style.width=p+"%";
					break;
				case 'TB':
					n.bar.style.height=p+"%";
					break;
				case 'BT':
					n.bar.style.height=p+"%";
					break;
			}			
		}
	}
};