$_.js.reg['0301']={
	a:['index','parent'],
	f:['ready'],
	b:function(n){},
	fs:function(n){
		$_.dispatchEvent(n,"ready");
	}
};