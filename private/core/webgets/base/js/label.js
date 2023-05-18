$_.js.reg['0010']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:['change', 'define', 'ready', 'onrefresh'],
	b:function(n){with(n){
    var f=n.children[0],
	  	  ff=(f ? f.children[0] : null);

    if(!ff)ff={nodeName:null};
    if(!f)f={nodeName:null};
    if(ff.nodeName=='SPAN') n.sub=ff;
    else if(f.nodeName=='SPAN') n.sub=f;
    else n.sub=n;

		n.caption=function(c){with(n){
			if(c===undefined || c==null)return sub.innerHTML.replace(/\t|\n|\r/g, '');
			sub.innerHTML=c;
			$_.dispatchEvent(n,"change");
			return true;
		}};

		n.valign=function(a){with(n){

		}};

		n.halign=function(a){with(n){

		}};

		n.refresh=function(){
			var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
			if(f !== false) eval(f);
			f = $_._getFormattedFields(n.field,n.field_format);
			if(f !== false) n.caption(f.replace(/\\x22/g,'"').replace(/\\x27/g,"'"));
			$_.dispatchEvent(n,"onrefresh");
		};

		$_.dispatchEvent(n,"define");
	}},

	fs:function(n){
		$_.dispatchEvent(n,"ready");

	}
};
