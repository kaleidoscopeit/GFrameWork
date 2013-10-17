$_.js.reg['0311']={
	a:['eval_field','eval_field_command'],
	f:['ready'],
	b:function(n){with(n){
		n.refresh=function(){
			$_.jsimport('system.phpjs.vsprintf');
      var fs = $_.js.reg['0310'].getfields(n.eval_field);
			if(fs != false) eval(vsprintf(n.eval_field_command,fs));
				
/*			if(fs = $_.js.reg['0310'].getfields(n.eval_field))
				eval(vsprintf(n.eval_field_command,fs));*/
		};
	}},
	fs:function(n){
	  n.dispatchEvent(n.ready);
  }
};