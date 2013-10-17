$$.js.reg['0130']={
  a:['preset','mode','class_in', 'class_normal', 'class_out'],
  f:[],
  b:function(n){with(n){
    n.c=children;
    n.cl=c.length;

    for(var x=0;x<cl;x++){$$.addClass(c[x], n.class_normal);}
    n.first=function(){n.show(0);};
    n.last=function(){n.show(n.children.length-1);};    
    n.next=function(si,e){
      e=e||'default';
      si=n.selectedIndex;
      if(si+1<n.children.length)si++;
      else{
        switch(n.mode){
          case'once':break;
          default:case'loop':si=0;
        } 
      }
      n.show(si,e);
    };

    n.previous=function(si,e){
      e=e||'default';
      si=n.selectedIndex;
      if(si>0)si--;
      else{
        switch(n.mode){
          case'once':break;
          default:case'loop':si=n.children.length-1;
        } 
      }
      n.show(si,e);
    };
    
    n.show=function(i,x,c,f){with(n){
      i=i>-1?i:0;
      if(n.selid){
      	if(n.selid<i)$$.addClass(c[n.selid], n.class_out);
      	if(n.selid>i)$$.addClass(c[n.selid], n.class_in);
      }

      try {
			  $$.removeClass(c[i], n.class_in);
			  $$.removeClass(c[i], n.class_out);
			}
		  catch(e){}

      // sets selected as the current shown panel, else exits if the panel doesn't exists
      if(i<cl){
        n.prev_selected=n.selected;
        n.selected=c[i];
        n.selectedIndex=i;
      }
      else return true;

      
      
      // make selected panel visible
      selected.style.display="";
      n.trans_end();
    }},
    
    n.trans_end=function(){
      // itherates through all child-nodes and hides all exept the selected in 'i'
      for(x=0;x<cl;x++){
        if(c[x]!=selected){
          //c[x].style.width="0px";
          //c[x].style.height="0px";
          c[x].style.display="none";
        }
      }
      
      // Executes panel's onshow code
      f=new Function(selected.getAttribute('onshow'));      
      f.call(selected);        
    };
  }},
  
  fs:function(n){n.show(n.preset|0);}
};