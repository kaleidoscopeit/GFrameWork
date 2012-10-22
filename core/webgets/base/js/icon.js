$_.lib.base_icon={
	// setup some variables
	c:[],a:['size','icon'],f:['ready'],
 	// funzione standard di avvio
	construct:function(t,s,ss){with(this){
		t=document.getElementsByTagName('div');

		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='base:icon'){
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
	build:function(n){with(n){
		n.fs=function(s){with(n){
			ico_nrm=$_gide.cre('img');
			ico_ovr=$_gide.cre('img');
			ico_sel=$_gide.cre('img');
		}};
		
		// do panning
		// sof+x-spt : start-offset+actual mouse position-start mouse position
		n.pn=function(e,x){with(n){
			y=$_gide.eve(e).clientY;
			us(sof+y-spt);
			$_gide.lib.pack_vpaned.rs();
		}};pn.n=n;
		
		// updates the relative position of the handle vs containter width
		// (cla.offsetWidth*offsetWidth/w) = new relative handle position
		n.rs=function(){with(n){
			us(cla.offsetHeight*offsetHeight/h);
			h=scrollHeight;
		}};rs.n=n;
		
		// cumulative sizes update (for code saving)
		n.us=function(ofs,nw){with(n){
			nw=n.scrollHeight-hdl.offsetHeight;
			cla.masz=cla.masz||nw;
			clb.masz=clb.masz||nw;
			cla.rng=(cla.misz>ofs)+(ofs>cla.masz);
			clb.rng=(clb.misz>ofs-hdl.offsetHeight)+(ofs-hdl.offsetHeight>clb.masz);
			if(cla.rng!="1"){
				cla.style.height=ofs+"px";
				hdl.style.top=ofs+"px";
				clb.style.height=offsetHeight-ofs-hdl.offsetHeight+"px";
			}
		}}
		
		$_gide.ade(window,"resize", function(){$_gide.lib.pack_vpaned.rs()});
	}},
	
	// broadcast function : call resize function of each hpaned webgets
	rs:function(){for(s in this.c)this.c[s].rs()}
};