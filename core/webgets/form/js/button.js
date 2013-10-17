$_.js.reg['0250'] = {
  a : ['eval_field', 'eval_field_command'],
  f : [],
  b : function(n) {
    with (n) {
      n.refresh = function() {
        if (n.eval_field == null)
          return;
        var field = n.eval_field.split(','), fs = [];
        $_.each(field, function(f, i) {
          f = f.split(':');
          eval('var row=' + f[0] + '.current_record');
          fs.push(row[f[1]]);
        });

        $_.jsimport('system.phpjs.vsprintf');
        eval(vsprintf(n.field_format, fs));
      };
    }
  },
  fs : function(n) {
  }
}; 