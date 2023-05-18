$_.js.reg['0100']={
  a:['eval_field','eval_field_command'],
  f:['ready'],
  b:function(n){with(n){
    n.refresh=function(){
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
    };
  }},
  fs:function(n){
    $_.dispatchEvent(n,"ready");
  }
};
