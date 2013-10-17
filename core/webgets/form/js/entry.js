$_.js.reg['0210'] = {
  a : ['field', 'field_format'],
  f : [],
  b : function(n) {
    with (n) {
      n.refresh = function() {
        var field = n.field.split(','), fs = [];
        $_.each(field, function(f, i) {
          f = f.split(':');
          eval('var row=' + f[0] + '.current_record');
          fs.push(row[f[1]]);
        });

        $_.jsimport('system.phpjs.vsprintf');
        n.value = vsprintf(n.field_format, fs);
      };
    }
  },
  fs : function(n) {
  }
}; 