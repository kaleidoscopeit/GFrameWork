$_.js.reg['0060']={
  a:['path'],
  f:['onclick','onchange'],
  b:function(n){with(n){
    n.c=n.children[2];
    n.i=n.children[0];
    n.value=i.value;

    n.setpath = function(p){
      p=p.split('/');
      if(p[0]=='')p.shift();
      i.value=n.value='/'+p.join('/');
      c.innerHTML='';
      
      while(p.length>0){
        var b=$_.cre('button');
        b.value='/'+p.join('/');
        i=p.pop();
        b.innerHTML=i;
        b.n=n;
        b.onclick=function(){this.n.setpath(this.value);};
        c.appendChild(b);
      }

      n.dispatchEvent(n.change);
    };

  }},
  
  fs:function(n){
    if(n.path!='')n.setpath(n.path);
  }
};