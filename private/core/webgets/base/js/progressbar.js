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
        $_.dispatchEvent(n,"change");
        return true;
      },

      n.refresh = function() {
        var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
        if(f !== false) eval(f);
        f = $_._getFormattedFields(n.field,n.field_format);
        if(f !== false) n.setProgress(f);
      };
    }
  },
  fs : function(n) {
    with (n) {
      n.bar = children[0];
    }
  }
};
