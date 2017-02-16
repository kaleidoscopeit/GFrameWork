$_.js.reg['0070'] = {
  a : ["view"],
  f : ["load","click"],
  b : function(n) {
    n.injJsBuf={};

    n.goto = function(v) {
      n.ns = _getViewParams(v);
      if (typeof n.ns.ns != "undefined") {
        console.log("Use of personalized namespace '" + n.ns.ns + "' in subview ");
        n.ns = n.ns.ns;}
      else
        n.ns = n.id;
      var x = $$.ajax({url:"?subview/" + v + "&ns=" + n.ns, callback:n.xhrcbk});
    };

    n.refresh = function() {
      n.goto(this.view);
    };

    n.back = function() {
      n.goto(n.history.stack.pop().pop());
    };

    n.xhrcbk = function(x,c,i,t){
      t=0;
      if(x.readyState==4&&x.status==200){
        c = x.responseText
             .match(/<!--[\s\S]*?-->/g)[0]
             .replace('<!--\n', '')
             .replace('\n-->', '')
             .split('\n\n');

        if(typeof c[0]!='undefined')n.injcss(c[0]);
        n.innerHTML = x.responseText;
        if(typeof c[1]!='undefined')n.injJs(c[1]);
        else n.initJs();
        n.dispatchEvent(n.load);
      }
      return true;
    };

    n.initJs = function(){
      /* initialize root namespace container */
      $$.webgets[n.ns]=[];

      /* launch initialization (HTML parsing) starting from this subview root */
      $$.each(n.childNodes,function(v,i){
        $$._wInit(v,n,n.ns);
      });

      /* binds enqueued events in the subview namespace */
      $$._flushBinds(n.ns);

      /* client side flush event only if a linked library exists */
      for(var s in $$.webgets[n.ns]) {
        if($$.js.reg[$$.webgets[n.ns][s].wid])
          $$.js.reg[$$.webgets[n.ns][s].wid].fs($$.webgets[n.ns][s]);
      }

      /* dispatch 'ready' event for the root of this subview */
      _w(n.ns + ":root").dispatchEvent(_w(n.ns + ":root").ready);
    };

    n.injcss = function(u) {
      /* remove previous styles */
      var a = document.getElementsByTagName('link');
      var h = document.getElementsByTagName("head")[0];

      for(var i = a.length-1;i>-1;i--){
        if(a[i].id == n.id) {
          h.removeChild(a[i]);
        }
      }

      u=u.split('\n');

      for(i in u){
        var l = $$.cre('link');
        l.type = 'text/css';
        l.id = n.id;
        l.rel = 'stylesheet';
        l.href = u[i];
        l.media = 'screen';
        h.appendChild(l);
      }
    };

    /* load javascript libraries */
    n.injJs = function(u)
    {
      u=u.split('\n');
      var i=u.length;
      while(u.length > 0){
        $$.importRawJs(u.pop(),function(s){
          if(s) {
            i--;
            if(i===0) n.initJs();
          }
        });
      }
    };
  },

  fs : function(n) {
    if (n.view !== '' && n.view !== null)
      n.goto(n.view);
  }
};
