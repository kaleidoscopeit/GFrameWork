$_.js.reg['0510']={
  a:['eval_field','eval_field_command','modal','modal_style','modal_class'],
  f:['ready','show','error','update','close'],
  b:function(n){with(n){
    n.refresh=function(){
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
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
      $_.dispatchEvent(n,"show");
    };

    n.hide=function(){
      n.style.opacity=n.shield.style.opacity='0';
      n.style.visibility=n.shield.style.visibility='hidden';
      $_.dispatchEvent(n,"close");
    };

    n.callback=function(args,s=true){
      if(n.cbkf == undefined) n.hide();
      else if(n.cbkf(args,s)!=false) n.hide();
    };

    n.setError=function(args){
      n.error_args=args;
      $_.dispatchEvent(n,"error");
    };

    n.updateDialog=function(args){
      n.update_args=args;
      $_.dispatchEvent(n,"update");
    };

    n.isActive=function(){
      if(n.style.opacity==1) return true;
      else return false;
    };
  }},

  fs:function(n){
    n.shield=$_.cre('div');
    n.shield.style.cssText="position:absolute;width:100%;height:100%;"
      + "left:0;top:0;" + n.modal_style;
    n.shield.className="w0510 " + n.modal_class;
    n.parentNode.insertBefore(n.shield,n);
    $_.dispatchEvent(n,"ready");
  }
};
