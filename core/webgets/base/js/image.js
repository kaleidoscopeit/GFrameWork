$_.js.reg['0020']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:[],
	b:function(n){with(n){
		n.refresh=function(){
			$_.jsimport('system.phpjs.vsprintf');
			if(fs = $_.js.reg['0310'].getfields(n.eval_field))
				eval(vsprintf(n.eval_field_command,fs));
			
			if(fs = $_.js.reg['0310'].getfields(n.field))
				n.src = vsprintf(n.field_format,fs);
		}
	}},
	fs:function(n){}
};