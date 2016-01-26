$_.js.reg['0070'] = {
  a : ["view"],
  f : ["load","click"],
  b : function(n) {
    n.injJsBuf={};

    n.goto = function(v) {
      if (v.indexOf("&ns=") > 1)
        console.log("Use of 'ns' in subview '" + n.id + "'");
      var x = $$.ajax({url:"?subview/" + v + "&ns=" + n.id, callback:n.xhrcbk});
    };

    n.refresh = function() {
      n.goto(this.view);
    };

    n.back = function() {
      with (this.history) {
        stack.pop();
        v = stack.pop();
      }
      this.goto(v);
    };

    n.xhrcbk = function(x,c,i,t){
      t=0;
      if(x.readyState==4&&x.status==200){
        c = x.responseText
             .match(/<!--[\s\S]*?-->/g)[0]
             .replace('<!--\n', '')
             .replace('\n-->', '')
             .split('\n\n');

        if(typeof(c[0])!='undefined')n.injcss(c[0]);
        n.innerHTML = x.responseText;
        if(typeof(c[1])!='undefined')n.injJs(c[1]);
        else n.initJs();
        n.dispatchEvent(n.load);
      }
      return true;
    };

    n.initJs = function(){
      $$._flushBinds(n.id);
      $$.webgets[n.id]=[];
      $$.each(n.childNodes,function(v,i){
        $$._wInit(v,n,n.id);
      });


      //$$._wInit(n,{childWebgets:[]},n.id);
      //$$.webgets[n.id].shift();
      for(var s in $$.webgets[n.id]) {
        if($$.js.reg[$$.webgets[n.id][s].wid])
          $$.js.reg[$$.webgets[n.id][s].wid].fs($$.webgets[n.id][s]);
      }

/*      $$.each($$._wGetPlain(n), function(elm,i){
          if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
        });*/
    };

    n.injcss = function(u) {
      /* remove previous styles */
      var a = document.getElementsByTagName('link')
      var h = document.getElementsByTagName("head")[0];

      for(var i = a.length-1;i>-1;i--){
        if(a[i].id == n.id) {
          h.removeChild(a[i]);
        }
      };

      u=u.split('\n');

      for(var i in u){
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
      var u=u.split('\n');
      var i=u.length;
      while(u.length > 0){
        $$.importRawJs(u.pop(),function(){
          i--;
          if(i==0) n.initJs();
        });
      }
    };
  },

  fs : function(n) {
    if (n.view != '')
      n.goto(n.view);
  }
};
