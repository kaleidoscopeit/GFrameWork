$_.js.reg['0510']={
  a:['eval_field','eval_field_command','modal','modal_style','modal_class'],
  f:['ready','show','error'],
  b:function(n){with(n){
    n.refresh=function(){
      fs = $$._getFormattedFields(n.eval_field,n.eval_field_command);
      if(fs !== false) eval(fs);
    };

    n.showDialog=function(p,c){
      if(n.modal!=null){
        n.shield.style.opacity='1';
        n.shield.style.visibility='visible';
      }

      if(n.modal=='blow') n.shield.onclick=n.hide;
      else n.shield.onclick=null;

      n.style.opacity='1';
      n.style.visibility='visible';

      n.cbkf=c;
      n.args=p;
      n.dispatchEvent(n.show);
    };

    n.hide=function(){
      n.style.opacity=n.shield.style.opacity='0';
      n.style.visibility=n.shield.style.visibility='hidden';
    };

    n.callback=function(args){
      if(n.cbkf == undefined) n.hide();
      else if(n.cbkf(args)!=false) n.hide();
    };

    n.setError=function(args){
      n.error_args=args;
      n.dispatchEvent(n.error);
    };

    n.isActive=function(){
      if(n.style.opacity==1) return true;
      else return false;
    };
  }},

  fs:function(n){
    n.shield=$$.cre('div');
    n.shield.style.cssText="position:absolute;width:100%;height:100%;"
      + "left:0;top:0;" + n.modal_style;
    n.shield.className="w0510 " + n.modal_class;
    n.parentNode.insertBefore(n.shield,n);
    n.dispatchEvent(n.ready);
  }
};
