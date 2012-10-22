$_.lib.pack_hpaned={
	// setup some variables
	c:[],a:['handle','hsize'],f:['onclick'],
 	// standard startup function
	construct:function(t,s,ss){with(this){
		// obtains special tag called "pack:hpaned"
		t=document.getElementsByTagName('div');

		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='pack:hpaned'){
				t[s].style.cssText="position:absolute;width:100%;height:100%;overflow:hidden;";
				for(ss in a)t[s][a[ss]]=t[s].getAttribute(a[ss]);
				// store locally a reference to the brand new object in an array
				c.push(t[s]);
				// builds-up the brand new object
				build(t[s]);
			}
		}}
	},


	// starts per-object interaction script (flush)
	flush:function(s,c){c=this.c;for(s in c)c[s].fs()},
	
	// builds javascript object
	build:function(n){with(n){
		n.fs=function(s){with(n){
			n.hdl=$_.cre('div');
			n.cla=childNodes[0];
			n.clb=childNodes[1];

			clb.style.right="0px";
	
			hdl.style.cssText="position:absolute;height:100%;background-color:lightgrey;";
			hdl.style.width=handle||"10px";
			hdl.selectable=false;
			hdl.n=n;
			
			// spt : absolute cursor start point
			// sof : initial handle offset
			hdl.onmousedown=function(e){
				n.spt=$_.eve(e).clientX;
				n.sof=cla.offsetWidth;
				$_.ade(document,"mousemove", n.pn);
				$_.ade(document, "mouseup", n.dr);
				// 'shields' for panes : prevents pan probem where there's an iframe inside panes
				n.sh1=$_.cre('div');
				n.sh2=$_.cre('div');
				n.sh1.style.cssText=n.sh2.style.cssText="position:absolute;left:0px;top0px;width:100%;height:100%;"
				cla.appendChild(n.sh1);
				clb.appendChild(n.sh2);
				return false;
			}

			insertBefore(hdl,clb);			
			n.w=scrollWidth;
			rs();us(hsize||n.w/2);
		}};
		
		// do panning
		// sof+x-spt : start-offset+actual mouse position-start mouse position
		n.pn=function(e,x){with(n){
			x=$_.eve(e).clientX;
			us(sof+x-spt);
			$_.lib.pack_hpaned.rs();
		}};pn.n=n;

		// cancel document attacced panning event
		n.dr=function(){
			cla.removeChild(n.sh1);
			clb.removeChild(n.sh2);
			$_.dde(document,"mousemove",n.pn);
			$_.dde(document,"mouseup",function(){});
		};
				
		// updates the relative position of the handle vs containter width
		// (cla.offsetWidth*offsetWidth/w) = new relative handle position
		n.rs=function(){with(n){
			us(cla.offsetWidth*offsetWidth/w);
			w=scrollWidth;
		}};rs.n=n;
		
		// cumulative sizes update (for code saving)
		n.us=function(ofs,nw){with(n){
			nw=n.scrollWidth-hdl.offsetWidth;
			cla.masz=cla.masz||nw;
			clb.masz=clb.masz||nw;
			cla.rng=(cla.misz>ofs)+(ofs>cla.masz);
			clb.rng=(clb.misz>ofs-hdl.offsetWidth)+(ofs-hdl.offsetWidth>clb.masz);
			if(cla.rng!="1"){
				cla.style.width=ofs+"px";
				hdl.style.left=ofs+"px";
				clb.style.width=offsetWidth-ofs-hdl.offsetWidth+"px";
			}
		}}
		
		$_.ade(window,"resize", function(){$_.lib.pack_hpaned.rs()});
	}},
	
	// broadcast function : call resize function of each hpaned webgets
	rs:function(c,s){c=this.c;for(s in c)c[s].rs()}
};
