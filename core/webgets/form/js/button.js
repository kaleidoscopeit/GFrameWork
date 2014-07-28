$_.js.reg['0250'] = {
	a:['field','field_format','eval_field','eval_field_command'],
	f:['define','flush'],
  b:function(n) {
		n.refresh=function(){
			$_.jsimport('system.phpjs.vsprintf');
      var fs = $_.js.reg['0310'].getfields(n.eval_field);
			if(fs !== false) eval(vsprintf(n.eval_field_command,fs));
				
			var fs = $_.js.reg['0310'].getfields(n.field);
			if(fs !== false) n.value = vsprintf(n.field_format,fs);
		};

		n.dispatchEvent(n.define);
  },
  fs:function(n) {
	  n.dispatchEvent(n.flush);
  }
}; 