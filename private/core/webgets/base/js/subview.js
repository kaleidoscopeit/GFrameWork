$_.js.reg['0070'] = {
  a : ["view"],
  f : ["load","click"],
  b : function(n) {
    n.injJsBuf={};

    n.goto = function(v) {
      n.ns = _getViewParams(v);
      if (typeof n.ns.ns != "undefined") {
        _dBG('E_INFO', 'subview,js', "Use of personalized namespace '" + n.ns.ns);
        n.ns = n.ns.ns;}
      else
        n.ns = n.id;
      var x = $_.ajax({url:"?subview/" + v + "&ns=" + n.ns, callback:n.xhrcbk});
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
        n.initJsready = 1;
        if(typeof c[1]!='undefined')n.injJs(c[1]);
        if(typeof c[2]!='undefined')n.injJs(c[2], true);
        else n.initJs();
        $_.dispatchEvent(n,"load");
      }
      return true;
    };

    n.initJs = function(){
      /* execute only if all JS library where loaded */
      if(n.initJsready != 0) {n.initJsready--; return};

      /* initialize root namespace container */
      $_.webgets[n.ns]=[];

      /* launch initialization (HTML parsing) starting from this subview root */
      $_.each(n.childNodes,function(v,i){
        $_._wInit(v,n,n.ns);
      });

      /* binds enqueued events in the subview namespace */
      $_._flushBinds(n.ns);

      /* client side flush event only if a linked library exists */
      for(var s in $_.webgets[n.ns]) {
        if($_.js.reg[$_.webgets[n.ns][s].wid])
          $_.js.reg[$_.webgets[n.ns][s].wid].fs($_.webgets[n.ns][s]);
      }

      /* dispatch 'ready' event for the root of this subview if exists */
      if(_w(n.ns + ":root").ready)
        _w(n.ns + ":root").dispatchEvent(_w(n.ns + ":root").ready);
    };

    n.injcss = function(u) {
      /* remove previous styles and clean u from already loaded syles */
      var a = document.getElementsByTagName('link');
      var h = document.getElementsByTagName("head")[0];
      u=u.split('\n');

      for(var i = a.length-1;i>-1;i--){
        var x = u.indexOf(a[i].getAttribute("href"));
        if(x > -1) delete u[x];

        if(a[i].id == n.id) {
          h.removeChild(a[i]);
        }
      }

      for(i in u){
        var l = $_.cre('link');
        l.type = 'text/css';
        l.id = n.id;
        l.rel = 'stylesheet';
        l.href = u[i];
        l.media = 'screen';
        h.appendChild(l);
      }
    };

    /* load javascript libraries (f : force reload) */
    n.injJs = function(u,f=false) {
      u=u.split('\n');
      var i=u.length;
      while(u.length > 0){
        $_.importRawJs(u.shift(),function(s){
          i--;
          if(i===0) n.initJs();
        }, n.ns, f);
      }
    };
  },

  fs : function(n) {
    if (n.view !== '' && n.view !== null) n.goto(n.view);
  }
};
