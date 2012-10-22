$_.lib.pack_modalw={
	// setup some variables
	c:[],a:['root'],f:['onclick'],
 	// standard hook funcion
	construct:function(t,s,ss){with(this){
		// gets all div from the page
		t=document.getElementsByTagName('div');
		
		// search for the subject(s) webget
		for(s=0;s<t.length;s++){
			if(t[s].getAttribute('type')=='pack:modalw'){
				for(ss in a)t[s][a[ss]]=t[s].getAttribute(a[ss]);
				// creates a directory of webgets inside this library
				c.push(t[s]);
				// attach to every webget the extra code below
				build(t[s]);
			}
		}}
	},

	// starts per-object interaction script (flush)
	flush:function(s,c){c=this.c;for(s in c)c[s].fs()},
	
	// attach the following code to the webget (n)
	build:function(n){ with(n){
		n.fs=function(s){with(n){}};
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
		};
	}}
}