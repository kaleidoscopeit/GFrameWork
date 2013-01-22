$_.js.reg['0030']={
  a:['ornt'],
  f:[],
  b:function(n){with(n){
    n.setProgress=function(p){
      switch(n.ornt){
        case 'LR':
          n.bar.style.width=p+"%";
          break;
        case 'RL':
          n.bar.style.width=p+"%";
          break;
        case 'TB':
          n.bar.style.height=p+"%";
          break;
        case 'BT':
          n.bar.style.height=p+"%";
          break;
      }      
    }
  }},
  fs:function(n){with(n){
    n.bar=firstChild;
  }}
};
