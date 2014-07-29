$_.js.reg['02A0'] = {
  a : [],
	f:['define','flush'],
  b : function(n) {
    n.firstChild.wstyle=n.style;
    n = n.firstChild;

    with (n) {
      n.items_insert = function(v, l, p) {
        var no = n.options;
        var nl = n.length;
        var se = no.selectedIndex;
        p = p ? p : nl + '';
        if (p.search(/ac/i) > -1)
          p = se > -1 ? se : nl;
        else if (p.search(/bc/i) > -1)
          p = se > -1 ? se + 1 : nl;
        p = p < 0 ? eval(nl + 1 + p) : p;
        if (p < 0)
          p = 0;
        if (p > nl)
          p = nl;
        for (var x = nl; x > p; x--) {
          no[x] = new Option(no[x - 1].text, no[x - 1].value);
        }
        no[p] = new Option(v, (l || l == 0 ? l : v));
        if (se >= p)
          se++;
        if (se > -1)
          no[se].selected = true;
        return 0;
      },
      
      n.items_remove = function(i) {
        with (this) {
          if (n.length == 0)
            return 0;
          // no items -> returns false

          if (!i && i != 0) {// no param gived -> deletes current item
            n.options[n.options.selectedIndex] = null;
            return 1;
          }

          if (isNaN(i))// i is a string -> try to delete those who have the same value
            for ( x = 0; x < n.options.length; x++) {
              if (i == n.options[x].value) {
                n.options[x] = null;
                return 1;
              }
            }

          if (n.length > i || n.length < 0)
            return 0;
          // if there's not index match -> returns false
          return 0;
          // returns false
        }
      },

      n.clear = function() {
        while (this.length != 0)
        this.items_remove(0);
      },
      
      n.populate = function(v) {
        console.log(v);
        if (v.length == null & $_.count(v) == 0)
          return false;
        n.clear();
        $_.tmp.n = n;
        $_.each(v, function(v, i) {
          $_.tmp.n.items_insert(v[0], (v[1] ? v[1] : v[0]));
        });
        return true;
      },
      
      // Reset to the original stack
      n.items_empty = function() {
        for (var i = n.options.length; i >= 0; i--) {
          n.options[i] = null;
        }
      };
    }
		n.dispatchEvent(n.parentElement.define);
  },
  fs : function(n) {
	  n.dispatchEvent(n.flush);
  }
}; 