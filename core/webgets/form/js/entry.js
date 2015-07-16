$_.js.reg['0210'] = {
  a : ['field', 'field_format'],
  f : ['flush'],
  b : function(n) {
    n.wstyle=n.parentElement.style;

    with (n) {
      n.refresh = function() {
        fs = $$._getFormattedFields(n.eval_field,n.eval_field_command);
        if(fs !== false) eval(fs);
        fs = $$._getFormattedFields(n.field,n.field_format);
        if(fs !== false) n.value=fs;
      };
    }
  },
  fs : function(n) {
    n.dispatchEvent(n.flush);
  }
};
