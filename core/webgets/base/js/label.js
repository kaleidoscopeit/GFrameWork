$$.js.reg['0010']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:['change', 'ready'],
	b:function(n){with(n){
	  var f=n.children[0],
	  	  ff=(f ? f.children[0] : null);

	if(!ff)ff={nodeName:null};
	if(!f)f={nodeName:null};
    if(ff.nodeName=='SPAN') n.sub=ff;
    else if(f.nodeName=='SPAN') n.sub=f;
    else n.sub=n;

		n.caption=function(c){with(n){
			if(c===undefined)return sub.textContent.replace(/\t|\n|\r/g, '');
			sub.textContent=c;
			n.dispatchEvent(change);
			return true;
		}};
		
		n.valign=function(a){with(n){

		}};

		n.halign=function(a){with(n){

		}};
		
		n.refresh=function(){
			$$.jsimport('system.phpjs.vsprintf');
      var fs = $$.js.reg['0310'].getfields(n.eval_field);
			if(fs != false) eval(vsprintf(n.eval_field_command,fs));
				
			var fs = $$.js.reg['0310'].getfields(n.field);
			if(fs != false) n.caption(vsprintf(n.field_format,fs));
		};
	}},
	
	fs:function(n){
    n.dispatchEvent(n.ready);
	}
};