$_.js.reg['0140']={
  a:['eval_field','eval_field_command'],
  f:['ready','changesrc'],
  b:function(n){with(n){
    n.refresh=function(){
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
    };

    n.goto=function(s){
      n.src=s;
      $_.dispatchEvent(n,"changesrc");
    }
  }},
  fs:function(n){
    $_.dispatchEvent(n,"ready");
  }
};
