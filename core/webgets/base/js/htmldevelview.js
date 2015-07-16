var $_={
  copyright:"(c)2005-2012 Gabriele Rossetti",
  licence:"GPL",
  env:{},
  lib:{},
  js:{reg:[]},
  tmp:{jsBindsFiFo:[],jsBindsNS:[]},
  webgets:[],

  /* main loop */
  _wInit:function(w,p,n){
    n=n||'root';
    var ch=w.childNodes,id,ln,r,a,f;
    w.wid=this.getAttribute(w,'wid');
    w.childWebgets=[];
    if(w.name) id=w.name;
    else if(typeof w.id == "string") id = w.id;

    //if(id)if(window[id] != w)window[id] = w;
    if(id)eval("window."+id+"=w;");
    if(w.wid) {
      p.childWebgets.push(w);
      w.parentWebget=p;
      w.nameSpace=n;
      this.webgets[n].push(w);
      this._wAttachJs(w);
    }

    if(ch.length>0)
      this.each(ch,function(v,i){$$._wInit(v,(w.wid?w:p),n);});
  },

  // get a plain array containing all child webgets of a given webget
  _wGetPlain:function(w){
    var cw=w.childNodes,tmp=[];
    w.wid=$$.getAttribute(w,'wid');
    if(w.wid) tmp.push(w);
    this.each(cw,function(v,i){
      tmp=tmp.concat($$._wGetPlain(v));
    });
    return tmp;
  },

  _wAttachJs:function(w){
    if(typeof this.js.reg[w.wid]=='undefined')return false;
    var r=this.js.reg[w.wid],a,f;

    for(a in r.a)w[r.a[a]]=w.getAttribute(r.a[a]);

    for(f in r.f){
      f=r.f[f];

      if(typeof(f) != 'function') w[f] = new Event(f);
      this.bindEvent(w, f, Function(w.getAttribute(f) || ''));
    }
    r.b(w);
    return true;
  },

  // space saving wrappers for common functions
  getAttribute:function(n,a){
    if(n.attributes)return n.getAttribute(a);
    return false},
  cre:function(n){return document.createElement(n);},
  gei:function(n){return document.getElementById(n);},

  // crossbrowser add event handler
  bindEvent:function(obj,eve,hdl){
    if(obj.attachEvent)obj.attachEvent("on"+eve,hdl);
    else obj.addEventListener(eve,hdl,false);
  },

  // crossbrowser add event handler
  unBindEvent:function(obj,eve,hdl){
    if(obj.attachEvent)obj.attachEvent("on"+eve,hdl);
    else obj.removeEventListener(eve,hdl,false);
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
    var c=0;for(var i in o)c++;return c;
  },

  // TODO :
  each:function(o,f){
    if(!o||!f)return false;
    if(o.length!=null)for(var i=0;i<o.length;i++)f(o[i],i);
    else for(var i in o)f(o[i],i);
    return true;
  },


  inArray:function(n,h){
    for(var i=0;i<h.length;i++)if(n==h[i])return i;
    return -1;
  },

  // XMLHttpRequest crossbrowser wrapper
  xhr:function(){
    try {return new ActiveXObject("Msxml2.XMLHTTP.6.0");} catch(e) {}
    try {return new ActiveXObject("Msxml2.XMLHTTP.3.0");} catch(e) {}
    try {return new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}
    try {return new XMLHttpRequest();} catch(e) {}
    throw new Error("This browser does not support XMLHttpRequest.");
  },

  // executes a remote call asynchronously, works similar the one at server side
  // eventually a string is returned, it will put in the default sub item '0'
  call:function(m,b,h,cbk) {
    this.jsimport('system.phpjs.serialize');
    var f=-1,p='b='+serialize(b),c=1,i,o;

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

    var callback = function(x){
      try{o=eval('(' + x.responseText + ')');}
      catch(e){
        alert('Parsing Call response failed (' + m + ') : '+x.responseText);
        return false;}

      // if response is a string put that in the default subitem '0'
      for(var i in o[1])c++;if(c==1)o[1]={0:o[1]};

      /* empty input buffer if 'stack' flag is not set */
      if(f==-1)for(p in b)delete b[p];

      /* merges responses values */
      for(p in o[1])b[p]=o[1][p];

      if(o[0] === null) {
        this.jsimport('system.phpjs.var_export');
        var_export(b,true);
      }

      // returns the call status
      if(cbk==null) return o[0];
      else cbk(b,o[0]);
    }

    var x = this.ajax({
      url:'?call/'+m.replace(/\./g,'/'),
      post:p,
      callback:(cbk!=null ? callback : null)
    });

    if(cbk==null) return callback(x);
    else return true;
  },

  // hotplug javascript code loader, l:library q:enqueue
  jsimport:function(l){
    if(!$$.tmp.jsimport)$$.tmp.jsimport=new Array;
    if($$.tmp.jsimport[l])return true;
    var xhr=this.xhr(),url='?lib/'+l.replace(/\./g,'/'),er;
    xhr.open('GET',url,false);
    xhr.setRequestHeader('Content-Type', 'application/text');
    xhr.send(null);
    try{eval('window.'+xhr.responseText);}
    catch(e){
      alert(xhr.responseText);er=1;
      throw('Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText);
    }
    if(!er){this.tmp.jsimport[l]=1;return true;}
    else{return false;}
  },

  importRawJs:function(l,c){
    if(l=='')return false;
    var x=this.xhr();
    x.open('GET',l,false);
    x.setRequestHeader('Content-Type', 'application/text');
    x.onreadystatechange=function(){
      if(x.readyState==4&&x.status==200){
        try{eval(x.responseText);if(c)c(true);}
        catch(e){
          if(c)c(false);
          throw('Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText);
        }
      }
      else if(c)c(false);
    };

    x.send(null);
    return true
  },

  /* aribtrary ajax request */
  ajax:function(args){
    var reqt = (args.post != null ? "POST" : "GET"),
        x=this.xhr(),
        cbks = (args.callback != null ? true : false);

    x.open(reqt,args.url,cbks);
    if(cbks) x.onreadystatechange = function() {
      if(x.readyState == 4 && x.status == 200)
        args.callback(x);
    }

    if(reqt == "POST"){
      x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      if(typeof args.post == 'object'){
        var p=[];
        $$.each(args.post, function(v,k){p.push(k+'='+v);});
        p.push.join('&');
      }

      else var p=args.post;
    }

    else if(reqt == "GET"){
      x.setRequestHeader('Content-Type', 'application/text');
      if(typeof args.get == 'object'){
        var p=[];
        $$.each(args.get, function(v,k){p.push(k+'='+v);});
        p.push.join('&');
      }
      else var p=args.get;
    }

    x.setRequestHeader("Content-length", p.length);
    x.send(p);
    if(cbks) return true
    else return x;
  },

  toggleClass:function(w,c,l,ll){
    l=w.className.split(' ');
    ll=l.indexOf(c);
    if(ll>-1)l.splice(ll,1);
    else l.push(c);
    w.className=l.join(' ');
  },

  addClass:function(w,c,l,ll){
    l=w.className.split(' ');
    ll=l.indexOf(c);
    if(ll==-1)l.push(c);
    w.className=l.join(' ');
  },

  removeClass:function(w,c,l,ll){
    l=w.className.split(' ');
    ll=l.indexOf(c);
    if(ll>-1)l.splice(ll,1);
    w.className=l.join(' ');
  },

  /* enqueue a bind */
  bind:function(t,f){
    t=t.split(".");
    t=[t.shift(),t.pop(),t.join('.')];
    this.tmp.jsBindsFiFo.push({ot:t[0],on:t[2],oa:t[1].replace('on',''),hd:f});
  },

  /* attach all binds */
  flushBinds:function(n){with($$){
    n=n||'root';

    /* unbind previous events */
    if(tmp.jsBindsNS[n]){
      while (tmp.jsBindsNS[n][0]){
        var i=tmp.jsBindsNS[n].pop();
          try{gei(i.on).removeEventListener(i.oa, i.hd);}
          catch (e) {}
      }
    }

    else tmp.jsBindsNS[n]=[];

    /* bind new events */
    while (tmp.jsBindsFiFo[0] != null){
      var i=tmp.jsBindsFiFo.pop(), obj=gei(i.on);

      switch(i.ot){
        case 'webget' :

          if(typeof(i.hd) == 'function') {
            if(typeof(obj[i.oa]) != 'function') {
              obj[i.oa] = new Event(i.oa);
            }
            obj.addEventListener(i.oa, i.hd);
            tmp.jsBindsNS[n].push(i);
          }

          else gei(i.on).setAttribute(i.oa, i.hd);

          break;
      }

      tmp.jsBindsFiFo[i]=null;
    }
  }}
};

var $$=$_;

/* convenient shortcut to open or goto a View */
var openView=function(v){
   window.location='?views/'+v;
};

/* return a webget by name */
var _w=function(w){with($$){
	for(var i=0;i<webgets.length;i++)
    if(webgets[i].id == w)
    	return webgets[i];

	return false;
}};

/* Client code initialization */
$$.bindEvent(window, "load", function(){with($$){
  document.body.id='root';
  flushBinds();

  webgets['root']=[];
  _wInit(document.body,{childWebgets:[]},'root');
//  for(var s in lib)lib[s].construct();


  if(typeof(document.body.ready) == 'object')
    document.body.dispatchEvent(document.body.ready);
}});

// Extract "GET" parameters from a JS include query string
var getParams = function() {
  // Find all script tags
  var URL = document.URL;
  var pa = URL.split("?").pop().split("&");
  // Look through them trying to find ourselves
  var p = {};
  for(var j=0; j<pa.length; j++) {
    var kv = pa[j].split("=");
    p[kv[0]] = kv[1];
  }
  return p;

  // No scripts match
  return {};
};
