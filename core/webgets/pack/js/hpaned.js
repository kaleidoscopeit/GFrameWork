$$.js.reg['0161']={
	a:['handle','hsize'],
	f:['onclick'],
	b:function(n){with(n){
	  this.cssText="position:absolute;width:100%;height:100%;overflow:hidden;";

		// do panning
		// sof+x-spt : start-offset+actual mouse position-start mouse position
		n.pn=function(e,x){with(n){
			x=$$.eve(e).clientX;
			us(sof+x-spt);
			$$.lib.pack_hpaned.rs();
		}};pn.n=n;

		// cancel document attacced panning event
		n.dr=function(){
			cla.removeChild(n.sh1);
			clb.removeChild(n.sh2);
			$$.unBindEvent(document,"mousemove",n.pn);
			$$.unBindEvent(document,"mouseup",function(){});
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
			cla.maxsize=cla.maxsize||nw;
			clb.maxsize=clb.maxsize||nw;
			cla.rng=(cla.minsize>ofs)+(ofs>cla.maxsize);
			clb.rng=(clb.minsize>ofs-hdl.offsetWidth)+(ofs-hdl.offsetWidth>clb.maxsize);
			if(cla.rng!="1"){
				cla.style.width=ofs+"px";
				hdl.style.left=ofs+"px";
				clb.style.width=offsetWidth-ofs-hdl.offsetWidth+"px";
			}
		}};

		$$.bindEvent(window,"resize", function(){$$.lib.pack_hpaned.rs();});

	}},
	fs:function(n){with(n){
			n.hdl=$$.cre('div');
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
				n.spt=$$.eve(e).clientX;
				n.sof=cla.offsetWidth;
				$$.bindEvent(document,"mousemove", n.pn);
				$$.bindEvent(document, "mouseup", n.dr);
				// 'shields' for panes : prevents pan probem where there's an iframe inside panes
				n.sh1=$$.cre('div');
				n.sh2=$$.cre('div');
				n.sh1.style.cssText=
				  n.sh2.style.cssText=
				    "position:absolute;left:0px;top0px;width:100%;height:100%;";
				cla.appendChild(n.sh1);
				clb.appendChild(n.sh2);
				return false;
			};

			insertBefore(hdl,clb);
			n.w=scrollWidth;
			rs();us(hsize||n.w/2);
	}},

 	// broadcast function : call resize function of each hpaned webgets
  rs:function(c,s){c=this.c;for(s in c)c[s].rs();}
};
