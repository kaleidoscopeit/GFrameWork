$_.js.reg['0250'] = {
	a:['field','field_format','eval_field','eval_field_command'],
	f:['define','ready','onrefresh'],
  	b:function(n) {
		n.refresh=function(){
			var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
			if(f !== false) eval(f);
			f = $_._getFormattedFields(n.field,n.field_format);
			if(f !== false) n.value=f;
			$_.dispatchEvent(n,"onrefresh");
		};
		$_.dispatchEvent(n,"define");

		n.caption=function(c){with(n){
			if(n.childNodes.length>1 || n.childNodes[0].nodeType != 3) return false;
			if(c===undefined || c==null)return n.innerHTML.replace(/\t|\n|\r/g, '');
			n.innerHTML=c;
			return true;
		}};
  	},
  	fs:function(n) {
		$_.dispatchEvent(n,"ready");
  }
};
