$_.js.reg['0030'] = {
  a : [ 'field', 'field_format', 'eval_field', 'eval_field_command',  'ornt' ],
  f : [ 'change' ],
  b : function(n) {
    with (n) {
      n.setProgress = function(p) {
        if(p == isNaN) return false;
        
        switch (n.ornt) {
          case 'LR':
            n.bar.style.width = p + "%";
            break;
          case 'RL':
            n.bar.style.width = p + "%";
            break;
          case 'TB':
            n.bar.style.height = p + "%";
            break;
          case 'BT':
            n.bar.style.height = p + "%";
            break;
        }

        this.progress = p;
        n.dispatchEvent(change);
        return true;
      },

      n.refresh = function() {
        $_.jsimport('system.phpjs.vsprintf');
        
        var fs = $_.js.reg['0310'].getfields(n.eval_field);
        if(fs != false) eval(vsprintf(n.eval_field_command,fs));
        
        var fs = $_.js.reg['0310'].getfields(n.field);
        if(fs != false) n.setProgress(vsprintf(n.field_format,fs));
      };
    }
  },
  fs : function(n) {
    with (n) {
      n.bar = children[0];
    }
  }
};
