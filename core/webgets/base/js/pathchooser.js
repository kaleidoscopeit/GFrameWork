$_.js.reg['0060']={
  a:['path'],
  f:['onclick'],
  b:function(n){with(n){
    c=n.children[2];
    i=n.children[0];
    n.value=i.value;
    
    n.setpath = function(p){
      p=p.split('/');
      if(p[0]=='')p.shift();
      i.value=n.value='/'+p.join('/');
      c.innerHTML='';
      
      while(p.length>0){
        b=$_.cre('button');
        b.value='/'+p.join('/');
        i=p.pop();
        b.innerHTML=i;
        b.n=n;
        b.onclick=function(){this.n.setpath(this.value)};
        if(p.length==0)b.style.marginLeft='-30px';
        c.appendChild(b);
      }
    }

  }},
  
  fs:function(n){
    if(n.path!='')n.setpath(n.path);
  }
};