$_.js.fx={
	obj:{},objc:0,
	
	fadein:function(o,p,c,i){with(this){
		if(o._fxi)clearInterval(o._fxi);
		objc++;
		obj[objc]=o;
		o.cbf=c;
		p=p||{}
		o._fxl=0;
		p.duration=p.duration||0.1;
		p.grit=p.grit||1;
		o._fxs=1/(25*p.grit*p.duration);
		o._fxi=setInterval("$_.js.fx._fadein('"+objc+"')",40/p.grit);
	}},

	_fadein:function(c){
		this.obj[c].style.opacity=this.obj[c]._fxl;
		this.obj[c]._fxl+=this.obj[c]._fxs;
		if(this.obj[c]._fxl>1){
			this.obj[c].style.opacity=1;
			clearInterval(this.obj[c]._fxi);
			if(this.obj[c].cbf)this.obj[c].cbf();
		}
	},
	
	fadeout:function(o,p,c,i){with(this){
		if(o._fxi)clearInterval(o._fxi);
		objc++;
		obj[objc]=o;
		o.cbf=c;
		p=p||{}
		o._fxl=1;
		p.duration=p.duration||0.1;
		p.grit=p.grit||1;
		o._fxs=1/(25*p.grit*p.duration);
		o._fxi=setInterval("$_.js.fx._fadeout('"+objc+"')",40/p.grit);
	}},

	_fadeout:function(c){
		this.obj[c].style.opacity=this.obj[c]._fxl;
		this.obj[c]._fxl-=this.obj[c]._fxs;
		if(this.obj[c]._fxl<0){
			this.obj[c].style.opacity=0;
			clearInterval(this.obj[c]._fxi);
			if(this.obj[c].cbf)this.obj[c].cbf();
		}
	},

	// 

	opc:function(obj){
	},
	
	blink:function(obj){
		
	}
};
