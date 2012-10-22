$_.js.reg['0010']={
	a:[],
	f:['onchange'],
	b:function(n){with(n){
		n.caption=function(c){with(n){
			if(c===undefined)return sub.textContent;
			sub.textContent=c;
			onchange();
		}};
		
		n.valign=function(a){with(n){

		}};

		n.halign=function(a){with(n){

		}};
	}},
	fs:function(n){
		if(n.firstChild)
		if(n.firstChild.firstChild)n.sub=n.firstChild.firstChild;
		else n.sub=n;
	}
};