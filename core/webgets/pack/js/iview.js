$_.js.reg['0150']={
  a:['view','tcn','tci','tco','tct'],
  f:['onload'],  
  b:function(n){with(n){
    n.ifa=childNodes[0];
    n.ifb=childNodes[1];
    n.ifc=childNodes[2];

    n.ifprev='0';
    n.ifcurr='1';
    n.ifnext='2';

    n.history={stack:[]}
    
    if(tcn && tci && tco){
      $_.toggleClass(ifa,tcn);
      $_.toggleClass(ifb,tcn);
      $_.toggleClass(ifc,tcn);
      $_.toggleClass(ifc,tco);
      $_.toggleClass(ifa,tci);
    }
      
    ifa.parent=ifb.parent=ifc.parent=n;
    ifa.onload=ifb.onload=ifc.onload=
      function(e){
        this.parent.show(this);

        this.contentWindow.parentView=window;
        //this.parent.onload();
      };
    
    n.goto=function(v){
      this.history.stack.push(v);
      v='?views/'+v;
      n.childNodes[n.ifnext].src=v;
      n.action='next';
    }

    n.back=function(){
      with(this.history){
        stack.pop();
        v=stack.pop();
      }
      this.goto(v);
    }
    
    n.show=function(i,o){
      $_.ade(n.childNodes[ifcurr], 'webkitTransitionEnd', n.trans_end);
      $_.ade(n.childNodes[ifcurr], 'oTransitionEnd', n.trans_end);
      $_.ade(n.childNodes[ifcurr], 'transitionend', n.trans_end);

      if(tcn && tci && tco){
        if(n.action=='next'){
          n.childNodes[ifnext].style.left=n.childNodes[ifnext].exleft;
          n.childNodes[ifnext].className=tcn;
          n.childNodes[ifcurr].className=tci+' '+tcn;
          n.childNodes[ifprev].className=tco+' '+tcn;
        }
      }

      var prev=n.ifprev;
      n.ifprev=n.ifcurr;
      n.ifcurr=n.ifnext;
      n.ifnext=prev;


      n.childNodes[ifcurr].id='curr';
      n.childNodes[ifprev].id='prev';
      n.childNodes[ifnext].id='next';
      
      //i.style.display='block';
    }
    
    n.trans_end=function(e){      
      if(n.childNodes[ifcurr]!=e.target){
        e.target.exleft=e.target.style.left;
        e.target.style.left='-100%';
      }
    }

  }},
  fs:function(n){  
    n.goto(n.view);
    
  }
};