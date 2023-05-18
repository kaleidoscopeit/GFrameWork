$_.js.reg['0311']={
	a:['eval_field','eval_field_command','show_if'],
	f:['ready', 'onrefresh'],
	b:function(n){with(n){
		n.refresh=function(){
			var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
			if(f !== false) eval(f);
			$_.dispatchEvent(n,"onrefresh");
		};
	}},
	fs:function(n){
		$_.dispatchEvent(n,"ready");
  }
};
