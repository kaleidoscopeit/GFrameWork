$_.js.reg['0290']={
  a:['field', 'eval_field', 'eval_field_command', 'disabled'],
  f:['change', 'define', 'ready', 'onrefresh'],
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
      if (n.disabled !== false && n.disabled != "false") return;
      if(input.value==1)input.value=0;
      else input.value=1;
      this.n.upd();
      $_.dispatchEvent(mask.n,"change");
    };

    input.onkeypress=function(){
      return false;
    };

		n.refresh=function(){
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
      f = $_._getFormattedFields(n.field,'{0}');
      if(f !== false) n.set(f);
      else n.set(this.value);
      $_.dispatchEvent(n,"onrefresh");
		};

    $_.dispatchEvent(n,"define");
  }},
  fs : function(n){
    if(n.disabled == null) n.disabled = "false";
    n.disabled = (n.disabled != "false" ? true : false);
    $_.dispatchEvent(n,"ready");
    n.set(n.value);
  }
};
