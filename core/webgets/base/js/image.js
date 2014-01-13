$_.js.reg['0020']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:['ready'],
	b:function(n){with(n){
		n.refresh=function(){
			$_.jsimport('system.phpjs.vsprintf');
      var fs = $_.js.reg['0310'].getfields(n.eval_field);
			if(fs != false) eval(vsprintf(n.eval_field_command,fs));
				
			var fs = $_.js.reg['0310'].getfields(n.field);
			if(fs != false) n.src = vsprintf(n.field_format,fs);
		};
	}},
	fs:function(n){
	  n.dispatchEvent(n.ready);
	}
};