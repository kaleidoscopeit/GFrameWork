$_.js.reg['0070'] = {
  a : ['view'],
  f : ['onload'],
  b : function(n) {
    n.injJsBuf={};
    
    n.goto = function(v,x) {
      x = $$.ajax({url:'?subview/' + v, callback:n.xhrcbk});
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

        try {
          n.innerHTML = x.responseText;

        } catch(e) {
          alert('Parsing Call response failed (' + this + ') : ' + x.responseText);
          return false;
        }


        if(typeof(c[1])!='undefined')n.injJs(c[1]);
        else n.initJs();
      }
      return true;
    };

    n.initJs = function(){
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
    };
    
    n.injcss = function(u) {
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

    n.injJs = function(u) {
      u=u.split('\n');
      for(var i in u){
        $$.importRawJs(u[i],function(){
          u[i]="";
          if(u.join('')=="")n.initJs();
        });
      }
    };


  },
  fs : function(n) {
    if (n.view != '')
      n.goto(n.view);
  }
}; 