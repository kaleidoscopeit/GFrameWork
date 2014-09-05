$_.js.reg['0290']={
  a:['field', 'eval_field', 'eval_field_command'],
  f:['change', 'define', 'flush'],
  b:function(n){with(n){
          
    n.lever=children[2].children[0];
    n.input=children[0];
    n.mask=children[3];
    mask.n=n;
    n.value=input.value;

    n.set=function(v){
      if(v===null || typeof v == 'string')return;
      v=(v>0?1:0);
      input.value=v;      
      n.upd();
    };
    
    n.upd=function(){
      if(input.value==0)lever.className="";  
      else lever.className="w0291";
      this.value=input.value;
    };
    
    mask.onmousedown=function(){
      this.n.input.focus();
      return false;      
    };
    
    mask.onmouseup=function(){
      if(input.value==1)input.value=0;
      else input.value=1;
      this.n.upd();
      mask.n.dispatchEvent(mask.n.change);
    };
    
    input.onkeypress=function(){
      return false;
    };

		n.refresh=function(){
			$$.jsimport('system.phpjs.vsprintf');
      var fs = $$.js.reg['0310'].getfields(n.eval_field);
			if(fs !== false) eval(vsprintf(n.eval_field_command,fs));
			
			var fs = $$.js.reg['0310'].getfields(n.field);
			if(fs !== false) n.set(fs[0]);
		};
		n.dispatchEvent(n.define);
  }},
  fs : function(n){
    n.dispatchEvent(n.flush);
  }
};