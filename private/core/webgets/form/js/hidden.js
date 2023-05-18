$_.js.reg['0220'] = {
  a : ['field', 'field_format','eval_field','eval_field_command'],
  f : ['ready','onrefresh'],
  b : function(n) {
    n.refresh = function() {
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
      f = $_._getFormattedFields(n.field,n.field_format);
      if(f !== false) n.value=f;
      $_.dispatchEvent(n,"onrefresh");
    };
  },
  fs : function(n) {
    $_.dispatchEvent(n,"ready");
  }
};
