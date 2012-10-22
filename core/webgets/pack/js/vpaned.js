$_.lib.pack_vpaned={
	// setup some variables
	c:[],a:['handle','vsize'],f:['onclick'],
 	// funzione standard di avvio
	construct:function(t,s,ss){with(this){
		// ottiene dall'HTML i tag speciali AJFORM
		t=document.getElementsByTagName('div');

		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='pack:vpaned'){
				t[s].style.cssText="position:absolute;width:100%;height:100%;overflow:hidden;";
				for(ss in a)t[s][a[ss]]=t[s].getAttribute(a[ss]);
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
			n.hdl=$_gide.cre('div');
			n.cla=childNodes[0];
			n.clb=childNodes[1];

			clb.style.bottom="0px";
	
			hdl.style.cssText="position:absolute;width:100%;";//background-color:lightgrey;";
			hdl.style.height=handle||"10px";
			hdl.selectable=false;
			hdl.n=n;
			
			// spt : absolute cursor start point
			// sof : initial handle offset
			hdl.onmousedown=function(e){
				n.spt=$_gide.eve(e).clientY;
				n.sof=cla.offsetHeight;
				$_gide.ade(document,"mousemove", n.pn);
				$_gide.ade(document, "mouseup", n.dr);
				// 'shields' for panes : prevents pan probem where there's an iframe inside panes
				n.sh1=$_gide.cre('div');
				n.sh2=$_gide.cre('div');
				n.sh1.style.cssText=n.sh2.style.cssText="position:absolute;left:0px;top:0px;width:100%;height:100%;"
				cla.appendChild(n.sh1);
				clb.appendChild(n.sh2);
				return false;
			}

			insertBefore(hdl,clb);			
			n.h=scrollHeight;
			rs();us(vsize||n.h/2);
		}};

		// do panning
		// sof+x-spt : start-offset+actual mouse position-start mouse position
		n.pn=function(e,x){with(n){
			y=$_gide.eve(e).clientY;
			us(sof+y-spt);
			$_gide.lib.pack_vpaned.rs();
		}};pn.n=n;

		// cancel document attacced panning event
		n.dr=function(){
			cla.removeChild(n.sh1);
			clb.removeChild(n.sh2);
			$_gide.dde(document,"mousemove",n.pn);
			$_gide.dde(document,"mouseup",function(){});
		};
				
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
	rs:function(c,s){c=this.c;for(s in c)c[s].rs()}
};