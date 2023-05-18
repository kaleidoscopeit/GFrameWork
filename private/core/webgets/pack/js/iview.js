$_.js.reg['0150']={
  a:['view','tcn','tci','tco','tct'],
  f:['onload'],
  b:function(n){with(n){
    n.ifa=$_.cre('iframe');
    n.ifb=$_.cre('iframe');
    n.ifc=$_.cre('iframe');
    n.appendChild(ifa);
    n.appendChild(ifb);
    n.appendChild(ifc);
    ifa.parent =
    ifb.parent =
    ifc.parent = n;
    n.ifprev='0';
    n.ifcurr='1';
    n.ifnext='2';

    n.history={stack:[]};

    if(tcn && tci && tco){
      $_.toggleClass(ifa,tcn);
      $_.toggleClass(ifa,tci);
      $_.toggleClass(ifb,tcn);
      $_.toggleClass(ifc,tcn);
      $_.toggleClass(ifc,tco);

    }

    ifa.onload = ifb.onload = ifc.onload = function(e) {
      if(this.src == "") return;
      this.parent.show(this);
      this.lastSrc = this.src;
    };

    n.goto = function(v) {
      this.history.stack.push(v);
      v = '?views/' + v;
      $_.bindEvent(n.children[n.ifnext], 'load', this.onload);
      n.children[n.ifnext].src = v;
      n.action = 'next';
    };

    n.back = function() {
      with(this.history){
        stack.pop();
        v = stack.pop();
      }
      this.goto(v);
    };

    n.show = function(i,o) {
      $_.bindEvent(n.children[ifcurr], 'webkitTransitionEnd', n.trans_end);
      $_.bindEvent(n.children[ifcurr], 'oTransitionEnd', n.trans_end);
      $_.bindEvent(n.children[ifcurr], 'transitionend', n.trans_end);

      if(tcn && tci && tco) {
        n.children[ifnext].style.left=n.children[ifnext].exleft;
        n.children[ifnext].className=tcn;
        n.children[ifcurr].className=tci+' '+tcn;
        n.children[ifprev].className=tco+' '+tcn;
      }

      var prev=n.ifprev;
      n.ifprev=n.ifcurr;
      n.ifcurr=n.ifnext;
      n.ifnext=prev;

      n.children[ifcurr].id='curr';
      n.children[ifprev].id='prev';
      n.children[ifnext].id='next';

      return true;
    };

    n.trans_end=function(e){
      if(n.children[ifcurr]!=e.target){
        e.target.exleft=e.target.style.left;
        e.target.style.left='-100%';
      }
    };

  }},
  fs:function(n){
    n.goto(n.view);
  }
};
