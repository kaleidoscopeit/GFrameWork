$_.js.reg['0180']={
	a:['root'],
	f:['onclick'],
	b:function(n){with(n){
    this.cssText+=";position:absolute;height:100%;top:0px;overflow:hidden;";
    
		n.show=function(){
			// if root is set will search for the root window ( trough pack:iframe )
			if(n.root){
				target=window
				while(!target.parent){target=window.parent}
			}
			
			this.style.visibility='visible';
			$_.js.fx.fadein(this)
		};

		n.hide=function(){
			// if root is set will search for the root window ( trough pack:iframe )
			if(n.root){
				target=window
				while(!target.parent){target=window.parent}
			}
			$_.js.fx.fadeout(this)
			this.style.visibility='hidden';
		}
	}},
	fs:function(n){with(n){
			
	}}
};