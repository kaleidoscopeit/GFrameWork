$_.js.reg['0070'] = {
  a : ['view'],
  f : ['onload'],
  b : function(n) {
    n.goto = function(v) {
      n.x = $_.xhr();
      n.x.onreadystatechange = n.xhrcbk;
      n.x.open('GET', '?subview/' + v, true);
      n.x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      //x.setRequestHeader("Content-length", p.length);
      n.x.setRequestHeader("Connection", "close");
      n.x.send();
    };

    n.back = function() {
      with (this.history) {
        stack.pop();
        v = stack.pop();
      }
      this.goto(v);
    };

    n.xhrcbk = function(x,c,i,t){
      x=n.x,t=0;
      if(x.readyState==4&&x.status==200){
        c = x.responseText
             .match(/<!--[\s\S]*?-->/g)[0]
             .replace('<!--\n', '')
             .replace('\n-->', '')
             .split('\n\n');
  
        n.incss(c[0]);

        try {
          n.innerHTML = x.responseText;

        } catch(e) {
          alert('Parsing Call response failed (' + this + ') : ' + x.responseText);
          return false;
        }


        n.injs(c[1]);

        $$.flushBinds(n.id);
        $$.webgets[n.id]=[];
        $$._wInit(n,{childWebgets:[]},n.id);
        $$.webgets[n.id].shift();
        for(var s in $$.webgets[n.id]) {
          if($$.js.reg[$$.webgets[n.id][s].wid])
            $$.js.reg[$$.webgets[n.id][s].wid].fs($$.webgets[n.id][s]);
        }
/*      $$.each($$.getPlainWebgets(n), function(elm,i){
          if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
        });*/
      }
      return true;
    };

    n.incss = function(u) {
      u=u.split('\n');
      for(var i in u){
        i=u[i];
        var h = document.getElementsByTagName("head")[0];
        var l = $_.cre('link');
        l.type = 'text/css';
        l.rel = 'stylesheet';
        l.href = i;
        l.media = 'screen';
        h.appendChild(l);
      }
    };

    n.injs = function(u) {
      u=u.split('\n');
       for(var i in u){
        i=u[i];
        $$.importRawJs(i);
      }
    };
  },
  fs : function(n) {
    if (n.view != '')
      n.goto(n.view);
  }
}; 