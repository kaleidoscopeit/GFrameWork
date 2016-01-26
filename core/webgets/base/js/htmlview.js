var $_={
  copyright:"(c)2005-2014 Gabriele Rossetti",
  licence:"GPL",
  env:{},
  lib:{},
  js:{reg:[]},
  tmp:{jsBindsFiFo:[],jsBindsNS:[],cssBindsNS:[]},
  webgets:{},

  /* main loop */
  _wInit:function(w,p,n)
  {
    n=n||'root';
    var ch=w.childNodes,id,ln,r,a,f;
    if(w.attributes) w.wid=w.getAttribute('wid');
    if(w.wid==9990) return;
    if(w.attributes) w.childWebgets=[];
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

//    this._wInitChilds(w,n);
    if(ch.length>0)
      this.each(ch,function(v,i){$$._wInit(v,(w.wid?w:p),n);});
  },

  _wInitChilds:function(w,n)
  {
    var ch=w.childNodes,p=w.parentWebget;

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

  /* eventually attach the linked library to the given webget */
  _wAttachJs:function(w){
    if(typeof this.js.reg[w.wid]=='undefined')return false;
    var r=this.js.reg[w.wid],a,f;

    /*  Reads from the webget the properties */
    for(a in r.a)w[r.a[a]]=w.getAttribute(r.a[a]);

    /*  Reads from the webget the 'function' passed as properties */
    for(f in r.f){
      f=r.f[f];
      fn=w.getAttribute('on' + f) || w.getAttribute(f) || null;

      /* Makes a function from 'f' attribute and bind it to the webget using the
       * standard bind functions. Read from the root HTML element, in order,
       * on[event], [event], no attribute */
       //if(typeof(f) != 'function') w[f] = new Event(f);
      this.bindEvent(w, f, Function(fn));
    }

    r.b(w);
    return true;
  },

  // space saving wrappers for common functions
  getAttribute:function(n,a){
    if(n.attributes)return n.getAttribute(a);
    return false;},
  cre:function(n){return document.createElement(n);},
  gei:function(n){return document.getElementById(n);},

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

  // TODO : this function is not recursive
  objConcat:function(o1,o2) {
    for (var key in o2) {
      o1[key] = o2[key];
    }
    return o1;
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
    var f=-1,p='b='+JSON.stringify(b),c=1,i,o;

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
      return true;
    };

    var x = this.ajax({
      url:'?call/'+m.replace(/\./g,'/'),
      post:p,
      callback:(cbk!=null ? callback : null)
    });

    if(cbk==null) return callback(x);
    else return true;
  },

  // hotplug javascript code loader, l:library q:enqueue
  jsimport:function(l,c){
    if(!$$.tmp.jsimport)$$.tmp.jsimport=new Array;
    if($$.tmp.jsimport[l])return true;
    var xhr=this.xhr(),url='?lib/'+l.replace(/\./g,'/'),er;
    xhr.open('GET',url,false);
    xhr.setRequestHeader('Content-Type', 'application/text');
    xhr.send(null);
    try{eval('window.'+xhr.responseText);}
    catch(e){
      console.log(xhr.responseText);er=1;
      throw('Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText);
    }
    if(!er){this.tmp.jsimport[l]=1;return true;}
    else{return false;}
  },

  /* let to hot-plug new javascript libraries on-demand, then execute the call-
   * back which depends on them. l:{array_of_libraries},c:callback */
  jsInclude:function(l,c){
    if(!$$.tmp.jsimport)$$.tmp.jsimport=new Array;

    if(l.length == 0) {
      //console.log("jsInclude : I'll execute the callback");
      c();
    }

    else if($$.tmp.jsimport[l[0]]) {
      var ll = l.shift();
      //console.log("jsInclude : '" + ll + "' already loaded.");
      this.jsInclude(l,c);
      return;
    }

    else {
      //console.log("jsInclude : '" + l[0] + "' not loaded.");
      var x=this.xhr(),url='?lib/'+l[0].replace(/\./g,'/');
      x.open("GET",url,true);
      x.setRequestHeader('Content-Type', 'application/text');
      x.onreadystatechange = function() {
        if(x.readyState == 4 && x.status == 200) {
          try{
            eval('window.'+x.responseText);
            //console.log("jsInclude : '" + l[0] + "' loaded.");
            $$.tmp.jsimport[l[0]]=1;}
          catch(e){
            throw('Cannot import '+l+' -> '+e+'; Response :'+x.responseText);
          }
          $$.jsInclude(l,c);
        }
      };
      x.send();
    }
  },

  /* download and execute a javaScript file */
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
          throw('Cannot import '+l+' -> '+e+'; Response :'+x.responseText);
        }
      }
      else if(c)c(false);

    };

    x.send(null);
    return true;
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
    };

    if(reqt == "POST"){
      x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      if(typeof args.post == 'object'){
        var p=[];
        $$.each(args.post, function(v,k){p.push(k+'='+v);});
        p.push.join('&');
      }

      else var p=args.post;
      x.setRequestHeader("Content-length", p.length);
    }

    else if(reqt == "GET"){
      x.setRequestHeader('Content-Type', 'application/text');
/*      if(typeof args.get == 'object'){
        var p=[];
        $$.each(args.get, function(v,k){p.push(k+'='+v);});
        p.push.join('&');
      }
      else var p=args.get;*/
    }

    x.send(p);
    if(cbks) return true;
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

  // crossbrowser add event handler, if the event is not native will attach a
  // function
  bindEvent:function(obj,eve,hdl)
  {
    if(!obj) {
      _dBG('E_ERROR', 'bindEvent', ['Passed Object is not valid',[
      obj,eve,hdl]]);
      return;
    };

    if(typeof(obj[eve]) == 'undefined')
      if(typeof(Event) == 'function')
        obj[eve] = new Event(eve);


    if(obj.attachEvent) {
      obj.attachEvent("on"+eve,hdl);
    }

    else
      obj.addEventListener(eve,hdl,false);
  },

  // crossbrowser remove event handler
  unBindEvent:function(obj,eve,hdl){
    if(obj.detachEvent)obj.detachEvent("on"+eve,hdl);
    else obj.removeEventListener(eve,hdl,false);
  },


  /* ----------------------- JS DYNAMIC ATTACH HANDLERS --------------------- */

  /* enqueue a bind into the FiFo buffer (TODO : evalutate the opportunity to
   * use a namespaced FiFo)*/
  bind:function(t,f){
    t=t.split(".");
    t=[t.shift(),t.pop(),t.join('.')];
    this.tmp.jsBindsFiFo.push({ot:t[0],on:t[2],oa:t[1].replace('on',''),hd:f});
  },

  /* attach all pending binds enqueued into FiFo. "n" attribute force a
     specific binds-set namespace owned by a subview to manage */
  _flushBinds:function(n){with($$)
  {
    if(!n) {
      _dBG('E_ERROR', '_flushBinds', 'No namespace');
      return false;
    };

    /* unbind previous events, also by namespace */
    if(tmp.jsBindsNS[n]){
      while (tmp.jsBindsNS[n][0]){
        var i=tmp.jsBindsNS[n].pop();
          try{gei(i.on).removeEventListener(i.oa, i.hd);}
          catch (e) {}
      }
    }

    else tmp.jsBindsNS[n]=[];

    /* bind enqueued new events */
    while (tmp.jsBindsFiFo[0] != null){
      var i=tmp.jsBindsFiFo.pop(), obj=gei(i.on);

      if(!obj) {
        _dBG('E_ERROR', '_flushBinds', ['Passed Object is not valid',i]);
        continue;
      };

      switch(i.ot){
        case 'webget' :

          if(typeof(i.hd) == 'function') {
            bindEvent(obj, i.oa, i.hd);
            tmp.jsBindsNS[n].push(i);
          }

          else obj.setAttribute(i.oa, i.hd);

          break;
      }

      tmp.jsBindsFiFo[i]=null;
    }
  }},

  _getFormattedFields:function(fld,frm){
    if(fld === null) return false;
    var fld=fld.split(','),fs=[];
    $$.each(fld,function(f,i){
      f=f.split(':');
      var row=_w(f[0]).current_record;
      if(typeof row[f[1]] == "string") // escape quotes if is a string
        fs.push(row[f[1]].replace(/"/g, '\\x22').replace(/'/g, '\\x27'));
      else if(f[1])                    // simply push if is not a string
        fs.push(row[f[1]]);
      else                             // simply push the record as is, i.e. in case recordset is a simple array
        fs.push(row);
    });

    if(frm=='') return fs;
    return frm.format(fs);
  }
};

var $$=$_;

/* convenient shortcut to open or goto a View */
var openView=function(v){
  window.location='?views/'+v;
};

/* return a webget by [namespace:name]  */
function _w(w){
  //  _dBG('E_DEBUG','_w',w);
  w = w.trim().split(':');
  var cNSp = [];

  if(w.length>1){
    typeof $$.webgets[w[0]] != 'undefined' ?
      cNSp = $$.webgets[w.shift()] :
      cNSp = $$.webgets['root'];

  }

  else {
    $$.each($$.webgets, function(o,v) {
      cNSp = cNSp.concat(o);
    });
  }

  w = w.join(':');

  for(var i = 0; i<cNSp.length; i++){
    if(cNSp[i].id == w) return cNSp[i];
  }

	return false;
};


/* debug function, following log level codes are given as examples, at the
 * current stage I don't know what code will be very useful.
 *
 * E_ALL       : all debug messages
 * E_SEVERE    : severe errors that require program exit
 * E_ERROR     : error messages that can't be recovered from but the program can
                 continue to run (e.g. failed call or function)
 * E_WARNING   : recoverable problem that you should be notified about
                 (e.g., invalid value in a configuration file, so you fell back
                 to the default).
 * E_INFO      : informational messages.
 * E_RPCS      : log RPCs messages
 * E_DEBUG     : general debugging messages.
 * E_BEHAVIOUR : trace the user activity (useful in remote debug)

 *
 * all log calls found in the javascript code of the application has to be
 * removed during the code minimization for deploy.
 */
function _dBG(l,a,m)
{
  if(($$.env.settings.cs_debug.search(l)
    || $$.env.settings.cs_debug.search('E_ALL')) != -2)
      console.log({'agent':a,'message':m});
};


// Extract "GET" parameters from a JS include query string // _getViewParams
function _getViewParams(n) {
  // Find all script tags
  var URL = document.URL;
  var pa = URL.split("?").pop().split("&");
  // Look through them trying to find ourselves
  var p = {};
  for(var j=0; j<pa.length; j++) {
    var kv = pa[j].split("=");
    p[kv[0]] = kv.length > 1 ? decodeURIComponent(kv[1]) : "";
  }
  return p;
};



/* Client side framework initialization (MAIN) */
$$.bindEvent(window, "load", function(){with($$)
{
  /* force document id (used as mnenonic reference and as namespace) */
  document.body.id='root';

  /* binds enqueue events in the 'root' namespace */
  _flushBinds('root');

  /* clean root namespace container */
  webgets['root']=[];

  /* launch initialization (page parsing) starting from 'body' TAG */
  _wInit(document.body,{childWebgets:[]},'root');

  /* legacy or testing code */
  //  for(var s in lib)lib[s].construct();

  /* client side flush event only if a linked library exists */
  for(var s in webgets['root']){
    if(js.reg[webgets['root'][s].wid])
      js.reg[webgets['root'][s].wid].fs(webgets['root'][s]);
  }

  /* dispatch 'ready' event for the body TAG (AKA 'root webget') */
  /* TODO : seems not follows the global rules for event dispatching */
  if(typeof(document.body.ready) == 'object')
    document.body.dispatchEvent(document.body.ready);
}});

/* Non framework functions */
String.prototype.format = function()
{
  var args = arguments;

  if(typeof args!='string')args=arguments[0];
  return this.replace(/\{(\d+)\}/g, function($0, $1)
  {
    return args[$1];
  });
};
