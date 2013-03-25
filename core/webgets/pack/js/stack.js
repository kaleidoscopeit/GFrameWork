$_.js.reg['0130']={
  a:['preset','mode'],
  f:['onclick'],
  b:function(n){with(n){
    n.c=childNodes;
    n.cl=c.length;
    n.first=function(){n.show(0)}
    n.last=function(){n.show(n.childNodes.length-1)}    
    n.next=function(si,e){
      e=e||'default';
      si=n.selectedIndex;
      if(si+1<n.childNodes.length)si++;
      else{
        switch(n.mode){
          case'once':break;
          default:case'loop':si=0;
        } 
      }
      n.show(si,e)
    }

    n.previous=function(si,e){
      e=e||'default';
      si=n.selectedIndex;
      if(si>0)si--;
      else{
        switch(n.mode){
          case'once':break;
          default:case'loop':si=n.childNodes.length-1;
        } 
      }
      n.show(si,e);
    }
    
    n.show=function(i,s,x,c,f){with(n){
      s=s||'default'
      i=i>-1?i:0;
      n.trans_type=s;
      n.trans_count=0;

      // sets selected as the current shown panel, else exits if the panel doesn't exists
      if(i<cl){
        n.prev_selected=n.selected;
        n.selected=c[i];
        n.selectedIndex=i;
      }
      else return(1);

      
      
      // make selected panel visible
      selected.style.display="";
      n.trans_end();
    }},
    
    n.trans_end=function(){
      // itherates through all child-nodes and hides all exept the selected in 'i'
      for(x=0;x<cl;x++){
        if(c[x]!=selected){
        //  c[x].style.width="0px";
        //  c[x].style.height="0px";
          c[x].style.display="none";
        }
      }
      
      // Executes panel's onshow code
      f=new Function($_.gea(selected,'onshow'));      
      f.call(selected);        
    }
  }},
  
  fs:function(n){n.show(n.preset|0)}
};