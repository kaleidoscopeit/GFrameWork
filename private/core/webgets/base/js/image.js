$_.js.reg['0020']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:['define','ready', 'onrefresh'],
	b:function(n){with(n){
		n.refresh=function(){
			var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
			if(f !== false) eval(f);
			f = $_._getFormattedFields(n.field,n.field_format);
			if(f !== false) n.src=f;
			$_.dispatchEvent(n,"onrefresh");
		};
		$_.dispatchEvent(n,"define");

	}},
	fs:function(n){
		$_.dispatchEvent(n,"ready");
	}
};
