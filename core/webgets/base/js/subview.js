$_.js.reg['0070']={
  a:['view'],
  f:['onload'],  
  b:function(n){
    n.goto=function(v){
    	n.x=$_.xhr();
    	n.x.onreadystatechange = n.xhrcbk;
      n.x.open('GET', '?subview/'+v , true);
      n.x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      //x.setRequestHeader("Content-length", p.length);
      n.x.setRequestHeader("Connection", "close");
      n.x.send();
    }

    n.back=function(){
      with(this.history){
        stack.pop();
        v=stack.pop();
      }
      this.goto(v);
    }
    
    n.xhrcbk=function(x,c,i,t){
      x=n.x;t=0
    	if (x.readyState==4 && x.status==200) {
    	  c = x.responseText
    	       .match(/<!--[\s\S]*?-->/g);
    	  if(c){
    	   c=c[0].split("\n");
    	   c.shift();
    	   c.pop();

          for(i=0;i<c.length;i++){
            if(c[i]==""){t=1}
            if(t==0)n.incss(c[i]);
            if(t==1)n.injs(c[i]);
          }
        }


        
        
        
        try{n.innerHTML=x.responseText}
            catch(e){
              alert('Parsing Call response failed (' + v + ') : '+x.responseText);
              return false;}
    	}    	
    }
 
    n.incss=function(u){
  	  var h = document.getElementsByTagName("head")[0];         
      var l = $_.cre('link');
      l.type = 'text/css';
      l.rel = 'stylesheet';
      l.href = u;
      l.media = 'screen';
      h.appendChild(l);      
    }
 
    n.injs=function(){
    }
  },
  fs:function(n){  
    if(n.view!='')n.goto(n.view);    
  }
};