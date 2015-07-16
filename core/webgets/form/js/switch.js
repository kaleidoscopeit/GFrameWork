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
      if(v===null)return;
      v=Number(v);
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
      fs = $$._getFormattedFields(n.eval_field,n.eval_field_command);
      if(fs !== false) eval(fs);
      fs = $$._getFormattedFields(n.field,'{0}');
      if(fs !== false) n.set(fs);
      else n.set(this.value);
		};
		n.dispatchEvent(n.define);
  }},
  fs : function(n){
    n.dispatchEvent(n.flush);
    n.set(n.value);
  }
};
