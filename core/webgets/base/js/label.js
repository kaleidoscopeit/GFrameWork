$$.js.reg['0010']={
	a:['field','field_format','eval_field','eval_field_command'],
	f:['change', 'define', 'ready'],
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
			n.dispatchEvent(change);
			return true;
		}};

		n.valign=function(a){with(n){

		}};

		n.halign=function(a){with(n){

		}};

		n.refresh=function(){
			fs = $$._getFormattedFields(n.eval_field,n.eval_field_command);
			if(fs !== false) eval(fs);
			fs = $$._getFormattedFields(n.field,n.field_format);
			if(fs !== false) n.caption(fs);
		};

		n.dispatchEvent(n.define);
	}},

	fs:function(n){
    n.dispatchEvent(n.ready);
	}
};
