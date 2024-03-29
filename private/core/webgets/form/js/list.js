$_.js.reg['02B0'] = {
  a:['optclass','field','field_format','eval_field','eval_field_command'],
	f:['ready','cut','paste','update','contentchange'],
  b:function(n) {
//    n = n.firstChild;

    with (n) {

      $_.bindEvent(n, 'change', function() {
        var opt = this.copy(), i;
        this.selected = [];
        for (i in opt)
        this.selected.push(opt[i].value);
      }, false),

      n.copy = function() {
        var out = [], opt = this.options, i;
        for (i in opt) {
          if (opt[i].selected)
            out.push(opt[i]);
        }
        return out;
      },

      n._refresh = function(obj) {
        this.values = [];
        this.captions = [];
        $_.each(this.options, function(o) {
          p = o.parentNode;
          p.values.push(o.value);
          p.captions.push(o.text);
          $_.dispatchEvent(n,"update");
        });
      },

      n.cut = function() {
        var out = [], opt = this.copy(), i;
        if(opt.length == 0) return [];
        while (opt.length > 0) {
          this.options[opt[0].index] = null;
          out.push(opt.shift());
        }
        this._refresh();
        $_.dispatchEvent(n,"contentchange");
        return out;
      },

      n.paste = function(o) {
        if(typeof o == "undefined") return;
        while (o.length > 0) this.add(o.shift());
        this._refresh();
        $_.dispatchEvent(n,"contentchange");
      },

      n.clear = function(r=true) {
        while (this.length > 0) this.remove(0);
        if(r) {
          this._refresh();
          $_.dispatchEvent(n,"contentchange");
        }
      },

      n.populate = function(v) {
        if (v.length == null & $_.count(v) == 0) return false;
        n.clear(false);
        $_.tmp.n = n;
        $_.each(v, function(v, i) {
          $_.tmp.n.item_push(v[0], (v[1] ? v[1] : v[0]));
        });
        this._refresh();
        $_.dispatchEvent(n,"contentchange");
        return true;
      },

      n.item_push = function(v, l) {
        var no = $_.cre('option');
        no.update = new Event('update');
        $_.bindEvent(no,'update', function() {
          if(this.parentElement.hasAttribute('item_eval_command'))
            eval(this.parentElement.getAttribute('item_eval_command')
              .format(this.value));
        });
        no.className = n.optclass;
        n.appendChild(no);
        no.value = v;
        no.text = ( l ? l : v);
      },

      n.sort = function() {
        var out = [], i, opt = this.options;
        for ( i = 0; i < opt.length; i++)
          out.push(opt[i]);
        out.sort(function(x, y) {
          var a = x.text, b = y.text, z = 0;
          if (a > b)
            z = 1;
          if (a < b)
            z = -1;
          return z;
        });
        n.clear(false);
        while (out.length > 0) this.add(out.shift());
        this._refresh();
      };
    }
  },

  fs : function(n) {
    n._refresh();
    $_.dispatchEvent(n,"ready");
  }
};
