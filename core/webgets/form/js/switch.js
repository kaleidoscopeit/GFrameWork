$_.js.reg['0290']={
  a:[],
  f:['onchange'],
  b:function(n){with(n){
    n.lever=children[2].children[0];
    n.input=children[0];
    n.mask=children[3];
    mask.n=n;
    n.value=input.value;
    mask.onmousedown=function(){
      this.n.input.focus();
      return false;
    };
    
    mask.onmouseup=function(){
      this.n.set('on') 
    };
    
    input.onkeypress=function(){
      return false;
    };
    
    n.set = function(status){with(this){
      if(input.value==1){
        input.value=0;
        lever.className="";  
      } else {
        input.value=1;
        lever.className="w0291";
      }

      this.value=input.value;
      this.onchange();
    }}
  }},
  fs:function(n){}
};