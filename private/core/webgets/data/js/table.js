$_.js.reg['0300']={
	a:['result_set'],
	f:['ready'],
	b:function(n){},
	fs:function(n){
		n.result_set=eval('(' + n.result_set + ')');
		$_.dispatchEvent(n,"ready");
	}
};