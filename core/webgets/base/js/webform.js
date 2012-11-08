var $_={
  copyright:"(c)2005-2012 Gabriele Rossetti",
  licence:"GPL",
  env:{},
  lib:{},
  js:{reg:[]},
  tmp:{},
  webgets:[],

  /* main loop */
  mloop:function(w,p){
    var ch=w.childNodes,id,ln,r,a,f;
    w.wid=$_.gea(w,'wid');
    w.childWebgets=[];
    if(w.name) id=w.name;
    else if(typeof w.id == "string") id = w.id
    if(id)eval('if(!window.'+id+')window.'+id+'=w;');
    if(w.wid) {
      p.childWebgets.push(w);
      w.parentWebget=p;      
      this.webgets.push(w)
      
      if(typeof $_.js.reg[w.wid]!='undefined'){
        r=$_.js.reg[w.wid];
        for(a in r.a)w[r.a[a]]=$_.gea(w,r.a[a]);
        for(f in r.f)w[r.f[f]]=new Function($_.gea(w,r.f[f]));
        r.b(w);
      }       
    }    
    if(ch.length>0) $_.each(ch,function(v,i){$_.mloop(v,(w.wid?w:p));});
  },
 
  // space saving wrapper for common functions
  gea:function(n,a){try{var ret=n.getAttribute(a);}catch(e){} return ret},
  cre:function(n){return document.createElement(n);},
  gei:function(n){return document.getElementById(n);},

  // crossbrowser add event handler
  ade:function(obj,eve,hdl){
    if(obj.attachEvent)obj.attachEvent("on"+eve,hdl);
    else obj.addEventListener(eve,hdl,false);
  },
 
  // crossbrowser remove event handler
  dde:function(obj,eve,hdl){
    if(obj.detachEvent)obj.detachEvent("on"+eve,hdl);
    else obj.removeEventListener(eve,hdl,false);
  },

  // Cross browser wrapper for event object
  eve:function(e){
    if (!e) e = window.event;
    return e;
  },
 
  // TODO :count an object length
  count:function(o){
    c=0;for(var i in o)c++;return c;
  },
 
  // TODO :
  each:function(o,f){
    var l=(isNaN(o.length)?$_.count(o):o.length);
    for(var i=0;i<l;i++)f(o[i],i);
  },
 
  // XMLHttpRequest crossbrowser wrapper 
  xhr:function(){
    try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
    catch (e) {}
    try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
    catch (e) {}
    try { return new ActiveXObject("Microsoft.XMLHTTP"); }
    catch (e) {}
    try { return new XMLHttpRequest(); }
    catch (e) {}
      throw new Error("This browser does not support XMLHttpRequest.");  
  },
 
  // executes a remote call asynchronously, works similar the one at server side
  // eventually a string is returned, it will put in the default sub item '0'
  call:function(m,b,h) {  
    this.jsimport('system.phpjs.unserialize');
    this.jsimport('system.phpjs.serialize');

    var x=this.xhr(),f=-1,p='b='+serialize(b),c=1,i,o;

    // look-up for 'stack' flag in order to manage it locally 
    // avoiding unnecessary back and forth traffic. Sets f to 1
    // and remove that flag in the request
    if(h){
      h=h.split(',');
      f=h.indexOf('stack');
      if(f>-1)h.splice(f,1);
      h=h.join(',');
      p+=h?'&h='+h:'';
    }  
  
    x.open('POST', '?call/'+m.replace(/\./g,'/') , false);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.setRequestHeader("Content-length", p.length);
    x.setRequestHeader("Connection", "close");
    x.send(p);
    try{o=unserialize(x.responseText);}
    catch(e){
      alert('Parsing Call response failed : '+x.responseText);
      return false;}

    // if response is a string put that in the default subitem '0'
    for(var i in o[1])c++;if(c==1)o[1]={0:o[1]};

    /* empty input buffer if 'stack' flag is not set */  
    if(f==-1)for(p in b)delete b[p];
  
    /* merges responses values */
    for(p in o[1])b[p]=o[1][p];

    // returns the call status
    return o[0];
  },

  // hotplug javascript code loader, l:library q:enqueue 
  jsimport:function(l){
    if(!$_.tmp.jsimport)$_.tmp.jsimport=new Array;
    if($_.tmp.jsimport[l])return true;
    var xhr=this.xhr(),url='?lib/'+l.replace(/\./g,'/'),er;
    xhr.open('GET',url,false);
    xhr.setRequestHeader('Content-Type', 'application/text');
    xhr.send(null);
    try{eval(xhr.responseText);}
    catch(e){
      alert(xhr.responseText);er=1;
      throw('Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText)
    }
    if(!er){this.tmp.jsimport[l]=1;return true}
    else{return false} 
  },
 
  toggleClass:function(w,c,l,ll){
    l=w.className.split(' ');
    ll=l.indexOf(c);
    if(ll>-1)l.splice(ll,1);
    else l.push(c);
    w.className=l.join(' ');
  },
 
  removeClass:function(w,c,l,ll){
    l=w.className.split(' ');
    ll=l.indexOf(c);
    if(ll>-1)l.splice(ll,1);
    w.className=l.join(' ');
  }
};


/* Client code initialization */
window.addEventListener("load", function(){
  var s;
  document.body.id='root';
  $_.mloop(document.body,{childWebgets:[]});

  for(s in $_.lib)$_.lib[s].construct();

  for(s in $_.webgets){
    if($_.js.reg[$_.webgets[s].wid])
      $_.js.reg[$_.webgets[s].wid].fs($_.webgets[s])
  }

  for(s in $_.lib)$_.lib[s].flush();
}, false);
