$_.js.reg['0311']={
	a:['eval_field','eval_field_command'],
	f:['ready'],
	b:function(n){with(n){
		n.refresh=function(){
			$$.jsimport('system.phpjs.vsprintf');
      var fs = $$.js.reg['0310'].getfields(n.eval_field);
			if(fs !== false) eval(vsprintf(n.eval_field_command,fs));
		};
	}},
	fs:function(n){
	  n.dispatchEvent(n.ready);
  }
};