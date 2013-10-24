$$.js.reg['0130']={
  a:['preset','mode','classfx'],
  f:[],
  b:function(n){with(n){
    n.c=children;
    n.cl=c.length;
    n.classfxNext=n.classfx+'_next';
    n.classfxPrev=n.classfx+'_prev';
    
    for(var x=0;x<cl;x++){$$.addClass(c[x], n.classfx);}
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

      c[i].style.visibility="";
      
      if(n.selectedIndex!=null){
      	if(n.selectedIndex<i) $$.addClass(c[n.selectedIndex], n.classfxPrev);
      	if(n.selectedIndex>i) $$.addClass(c[n.selectedIndex], n.classfxNext);
      }

      try {
        $$.removeClass(c[i], n.classfxNext);
        $$.removeClass(c[i], n.classfxPrev);
      }
      
		  catch(e){}
		  
      //n.addEventListener('webkitTransitionEnd', n.trans_end, false );
      n.transition = n.addEventListener('transitionend', n.trans_end, false );
      
      // sets selected as the current shown panel, else exits if the panel doesn't exists
      if(i<cl){
        n.prev_selected=n.selected;
        n.selected=c[i];
        n.selectedIndex=i;
      }
      else return true;
    }},

    n.trans_end=function(){
      // iterates through all child-nodes and hides all except the selected in 'i'
      for(x=0;x<cl;x++){
        if(c[x]!=selected){          
         $$.removeClass(c[x], classfxNext);
         $$.removeClass(c[x], classfxPrev);
         if(selectedIndex<x)$$.addClass(c[x], classfxNext);
         else $$.addClass(c[x], classfxPrev);
         c[x].style.visibility="hidden";
        }       
      }

      this.removeEventListener('transitionend', n.trans_end);

      // Executes panel's on-show code
      if(selected.hasAttribute('onshow')) {
        f=new Function(selected.getAttribute('onshow'));      
        f.call(selected);
      }        
    };
  }},
  
  fs:function(n){
    n.show(n.preset|0);
    n.trans_end();}
};