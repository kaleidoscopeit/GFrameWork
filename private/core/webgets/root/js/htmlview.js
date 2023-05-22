var $_={
  copyright:"(c)2005-2014 Gabriele Rossetti",
  licence:"GPL",
  env:{},
  lib:{},
  js:{reg:[]},
  tmp:{jsBindsFiFo:[],jsBindsNS:[],cssBindsNS:[],currNS:"root"},
  webgets:{},

  /* main loop */
  _wInit:function(w,p,n)
  {
    n=n||'root';
    var ch=w.childNodes,id,ln,r,a,f;
    if(w.attributes) {
      w.wid=w.getAttribute('wid');
      if(w.wid==9990) return;
      w.childWebgets=[];
    }

    if(w.name) id=w.name;
    else if(typeof w.id == "string") id = w.id;
    if(id && id != "") eval("window['"+id+"']=w;");
    if(w.wid) {
      p.childWebgets.push(w);
      w.parentWebget=p;
      w.nameSpace=n;
      this.webgets[n].push(w);
      this._wAttachJs(w);
    }

    if(ch.length>0)
      this.each(ch,function(v,i){$_._wInit(v,(w.wid?w:p),n);});
  },

  _wInitChilds:function(w,n)
  {
    var ch=w.childNodes,p=w.parentWebget;

    if(ch.length>0)
      this.each(ch,function(v,i){$_._wInit(v,(w.wid?w:p),n);});
  },

  // get a plain array containing all child webgets of a given webget
  _wGetPlain:function(w){
    var cw=w.childNodes,tmp=[];
    w.wid=$_.getAttribute(w,'wid');
    if(w.wid) tmp.push(w);
    this.each(cw,function(v,i){
      tmp=tmp.concat($_._wGetPlain(v));
    });
    return tmp;
  },

  /* eventually attach the linked library to the given webget */
  _wAttachJs:function(w){
    if(typeof this.js.reg[w.wid]=='undefined')return false;
    var r=this.js.reg[w.wid],a,f;

    /*  Reads from the webget the properties */
    for(a in r.a)w[r.a[a]]=w.getAttribute(r.a[a]);

    /* Reads from the webget the 'function' passed as properties
     * then for each function passed try to attach it */
    for(f in r.f){
      f=r.f[f];
      var fn=w.getAttribute('on' + f) || w.getAttribute(f) || "";

      /* Makes a function from 'f' attribute and bind it to the webget using the
       * standard bind functions. Read from the root HTML element, in order,
       * on[event], [event], no attribute */
       //if(typeof(f) != 'function') w[f] = new Event(f);
      if(fn == "") continue;
      this.bindEvent(w, f, Function(fn));

      /* remove tag attribute to avoid event bouncing */
      //w.removeAttribute('on' + f);
      //w.getAttribute(f);
    }

    r.b(w);
    return true;
  },

  /* ------------ SPACE SAVING WRAPPERS FOR COMMON FUNCTIONS ---------------- */
  getAttribute:function(n,a){return (n.attributes?n.getAttribute(a):false);},
  cre:function(n){return document.createElement(n);},
  gei:function(n){return document.getElementById(n);},
  getn:function(n){return document.getElementsByTagName(n);},
  eve:function(e){return e || window.event;},                                   // Cross browser wrapper for event object
  count:function(o){var c=0;for(var i in o)c++;return c;},                      // Count the length of an object

  // Executes a given function(f) for each element of an array or an object(o).
  each:function(o,f,i){
    if(!o||!f)return false;
    if(o.length!==undefined)for(i=0;i<o.length;i++)f(o[i],i);
    else for(i in o)f(o[i],i);
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
    // If the call URL is empty the imput buffer is directly dump to the ouput and
    // the remote call is not performed.
    if(m=='' || m === undefined) {
      if(cbk === undefined) return b;
      else cbk(b,true);
      return true;
    }

    var f=-1,p='b='+encodeURIComponent(JSON.stringify(b)),c=1,i,o;

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
      try{o = eval('(' + x.responseText + ')');}
      catch(e){
        alert('Parsing Call response failed (' + m + ') : ' + x.responseText);
        return false;}

      /* if response is not an object and the required response type implies to
         join the result with the request put that in the default subitem '0'.*/
      if((typeof o[1] != 'object' || o[1] == null) && f>-1) o[1]={0:o[1]};

      /* empty input buffer if 'stack' flag is not set */
      if(f==-1) for(p in b) delete b[p];

      /* merges responses values if response is an object and not a "null" */
      if(typeof o[1] == 'object' && o[1] != null) for(p in o[1]) b[p] = o[1][p];
      else b = o[1];

      if(o[0] === null) {
        console.log("RPC call error, check following logs.");
        console.log(b);
      }

      // returns the call status
      if(cbk === undefined) return o[0];
      else cbk(b,o[0]);
      return true;
    };

    var x = this.ajax({
      url : '?call/'+m.replace(/\./g,'/'),
      post : p,
      callback : (cbk !== undefined ? callback : undefined)
    });

    if(cbk === undefined) return callback(x);
    else return true;
  },

  // hotplug javascript code loader, l:library q:enqueue
  jsimport:function(l,c){
    if(!$_.tmp.jsimport)$_.tmp.jsimport=[];
    if($_.tmp.jsimport[l])return true;
    var xhr=this.xhr(),url='?lib/'+l.replace(/\./g,'/'),er;
    xhr.open('GET',url,false);
    xhr.setRequestHeader('Content-Type', 'application/octet-stream');
    xhr.send(null);
    try{window.eval('window.'+xhr.responseText);}
    catch(e){
      er=1;
      throw('[jsimport] Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText);
    }
    if(!er){this.tmp.jsimport[l]=1;return true;}
    else{return false;}
  },

  /* let to hot-plug new javascript libraries on-demand, then execute the call-
   * back which depends on them. l:{array_of_libraries},c:callback */
  jsInclude:function(l,c){
    if(!$_.tmp.jsimport)$_.tmp.jsimport=[];

    if(l.length === 0) {
      _dBG('E_DEBUG', 'jsInclude', "I'll execute the callback");
      c(false);
    }

    else if($_.tmp.jsimport[l[0]]) {
      var ll = l.shift();
      _dBG('E_INFO', 'jsInclude', "'" + ll + "'  loaded.");
      this.jsInclude(l,c);
      return;
    }

    else {
      //console.log("jsInclude : '" + l[0] + "' not loaded.");
      var x=this.xhr(),url='?lib/'+l[0].replace(/\./g,'/');
      x.open("GET",url,true);
      x.setRequestHeader('Content-Type', 'application/octet-stream');
      x.onreadystatechange = function() {
        if(x.readyState == 4 && x.status == 200) {
          try{
            window.eval('window.'+x.responseText);
            _dBG('E_INFO', 'jsInclude', "'" + l[0] + "'  loaded.");
            $_.tmp.jsimport[l[0]]=1;}
          catch(e){
            throw('[jsInclude] Cannot import '+l+' -> '+e+'; Response :'+x.responseText);
          }
          $_.jsInclude(l,c);
        }
      };
      x.send();
    }
  },

  isJsIncluded:function(l){
    if(typeof this.tmp.jsimport[l] != "undefined") return true;
    return false;
  },

  /* download and execute a javaScript file.
      l:JS path, c:callback, ns:namespace, f:force reload */
  importRawJs:function(l,c,ns,f = false){
    if(!$_.tmp.jsimport)$_.tmp.jsimport=[];
    if(typeof c == "function") var h = true;

    if(l=='') {
      _dBG('E_INFO', 'importRawJs', 'library name empty');
      if(h)c(false);
      return false;
    }

    else if($_.tmp.jsimport[l] && !f) {
      _dBG('E_INFO', 'importRawJs', "'" + l + "' already loaded." );
      if(h)c(true);
    }

    else {
      var x=this.xhr();
      x.open('GET',l,true);
      x.onreadystatechange=function(){
        if(x.readyState==4 && x.status==200){
          try{
            /* launch the imported code and eventually sets the current nameSpace */
            var r = x.responseText;
            if(ns) {
              r = "$_.tmp.currNS='" + ns + "';"
                + r
                + ";$_.tmp.currNS='root';";
            }
            _dBG('E_DEBUG', 'importRawJs', "Evalued code : " + r );
            window.eval(r);
            if(h)c(true);
            $_.tmp.jsimport[l]=1;
          }
          catch(e){
            //if(typeof c == "function")c(true);
            throw('[importRawJs] Cannot import ' + l + ' -> '
                  + e + '; Response :' + r);
          }
        }

        else if(x.readyState==4 && x.status!=200 && h) {
          c(false);
        }
      };
      x.send(null);
    }
    return true;
  },

  // executes the special query in order to check the session status */
  checkSession:function(cbk,env) {
    if(typeof cbk != 'function') return false;

    var callback = function(x){
      cbk(x.responseText);
    };
    var x = this.ajax({
      url : '?cksess' + (env==true? '&env' : ''),
      callback : callback
    });
  },

  /* aribtrary ajax request */
  ajax:function(args){
    var reqt = (args.post !== undefined ? "POST" : "GET"),
        x=this.xhr(),
        cbks = (args.callback !== undefined ? true : false);

    x.open(reqt,args.url,cbks);
    if(cbks) x.onreadystatechange = function() {
      if(x.readyState == 4 && x.status == 200)
        args.callback(x);
    };

    if(reqt == "POST"){
      x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      if(typeof args.post == 'object'){
        var p=[];
        $_.each(args.post, function(v,k){p.push(k+'='+v);});
        p.push.join('&');
      }

      else var p=args.post;
    }

    else if(reqt == "GET"){
      x.setRequestHeader('Content-Type', 'application/octet-stream');
/*      if(typeof args.get == 'object'){
        var p=[];
        $_.each(args.get, function(v,k){p.push(k+'='+v);});
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

    if(typeof(hdl) !== 'function') {
      _dBG('E_ERROR', 'bindEvent', ['Passed Handle is not a function',[
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

    _dBG('E_DEBUG', 'bindEvent', ["Bound event", obj, eve, hdl.toString()]);
  },

  // crossbrowser remove event handler
  unBindEvent:function(obj,eve,hdl){
    if(obj.detachEvent)obj.detachEvent("on"+eve,hdl);
    else obj.removeEventListener(eve,hdl,false);
  },


  // try to dispatch a webget event
  dispatchEvent(n,event){
    if(typeof n[event] != "undefined") n.dispatchEvent(n[event]);
  },

  /* ----------------------- JS DYNAMIC ATTACH HANDLERS --------------------- */

  /* enqueue a bind into the FiFo buffer
   * accepts -> t : webget(s) pointer in dotted notation (see below)
   *            f : function to be attached
   *            n : namespace of the bound stack
   *
   * 't' may be a string to point a single webget or an array of strings
   * ot : object_type, on : object_name, oe : object_event, hd : function
   */

  bind:function(t,f,n){
    if(typeof t == "string") t = [t];
    while(t.length > 0){
      _dBG('E_DEBUG', 'bind', ["Enqueue event for webget '" + t[0] + "'", f.toString()]);
      t[0]=t[0].split(".");
      t[0]=[t[0].shift(),t[0].pop(),t[0].join('.')];
      var ff=this.tmp.jsBindsFiFo;var ns=n||"root";
      if(typeof ff[ns] == "undefined") ff[ns]=[];
      ff[ns].push({'ot':t[0][0],'on':t[0][2],'oe':t[0][1].replace('on',''),'hd':f});
      t.shift();
    };
  },

  /* attach all pending binds enqueued into FiFo. "n" attribute force a
     specific binds-set namespace owned by a subview to manage */
  _flushBinds:function(n){with($_)
  {
    if(!n) {
      _dBG('E_ERROR', '_flushBinds', 'No namespace');
      return false;
    };

    /* unbind previous events, also by namespace */
    if(tmp.jsBindsNS[n]){
      while (typeof tmp.jsBindsNS[n][0] != "undefined"){
        var i=tmp.jsBindsNS[n].pop();
        try{_w(i.on).removeEventListener(i.oe, i.hd);}
        catch (e) {}
      }
    }

    else tmp.jsBindsNS[n]=[];

    /* bind enqueued new events */
    if(typeof tmp.jsBindsFiFo[n] == "undefined") return;
    while (tmp.jsBindsFiFo[n][0] != null){
      var i=tmp.jsBindsFiFo[n].pop(), obj=_w(i.on);

      if(!obj) {
        _dBG('E_ERROR', '_flushBinds', ['Passed Object is not valid',i]);
        continue;
      };

      switch(i.ot){
        case 'webget' :

          if(typeof(i.hd) == 'function') {
            bindEvent(obj, i.oe, i.hd);
            tmp.jsBindsNS[n].push(i);
          }

          else obj.setAttribute(i.oe, i.hd);

          break;
      }

      tmp.jsBindsFiFo[n][i]=null;
    }
  }},

  _getFormattedFields:function(fld,frm){
    if(fld === null) return false;
    fld = fld.split(',');
    var fs = [];

    $_.each(fld,function(f,i){
      f = f.split(':');
      var row = _w(f[0]).current_record;
      if(typeof row[f[1]] == "string")                                          // escape quotes and other characters if is a string
        fs.push(row[f[1]].replace(/"/g, '\\x22')
                         .replace(/'/g, '\\x27')
                         .replace(/\n/g,'\\n'));
      else if(f[1])                                                             // simply push if is not a string
        fs.push(row[f[1]]);
      else                                                                      // simply push the record as is, i.e. in case recordset is a simple array
        fs.push(row);
    });

    if(frm === '') return fs;
    return frm.format(fs);
  }
};

/* convenient shortcut to open or goto a View */
var openView=function(v){
  window.location='?views/'+v;
};

/* return a webget by [namespace:name]  */
function _w(w)
{
  _dBG('E_DEBUG','_w',w);
  w = w.trim().split(':');
  var cNSp = [],e = $_.webgets;

  if(w.length>1)
    cNSp = (typeof e[w[0]] !== "undefined" ? e[w.shift()] : e.root);
  else
    $_.each(e, function(o,v) { cNSp = cNSp.concat(o);});

  w = w.join(':');

  for(var i = 0; i<cNSp.length; i++){
    if(cNSp[i].id == w) return cNSp[i];
  }

	return false;
}

/* top level shortcut to the call function in order to uniform the syntax with
   the php code */
function _call(m,b,h,cbk) {return $_.call(m,b,h,cbk)};

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

 * WARNING performance issue !!!
 * All log calls found in the javascript code of the application has to be
 * removed during the code minimization for deploy.
 */
function _dBG(l,a,m)
{
  var d = "";
  if($_.env.settings) d = $_.env.settings.cs_debug;
  if((d.search(l) > -1 || d.search('E_ALL')) > -1)
    console.log({'debug':'','agent':a,'message':m});
}


/* Extract "GET" parameters from a JS include query string
 * n : a presonalized URL can be passed
 */
function _getViewParams(n) {
  // Find all script tags
  var URL = (typeof n != "undefined" ? n : document.URL);
  var pa = URL.split("?").pop().split("&");
  // extract the URI of the view
  var p = {};
  p.CALL_URN = pa.shift().split("/");
  p.CALL_URN.shift();
  p.CALL_URN = p.CALL_URN.join(".");

  // Look through them trying to find ourselves
  for(var j=0; j<pa.length; j++) {
    var kv = pa[j].split("=");
    p[kv[0]] = kv.length > 1 ? decodeURIComponent(kv[1]) : "";
  }

  return p;
}

/* set or get the view (or iframe subview) cache in the current page $_.env
 * - if value is set, the function act as setter, otherwise act as getter
 */
function _envCache(name, value) {
  if(typeof name == 'undefined') {
    console.log("_envCache : called without parameters");
    return;
  }

  // serach for the root page
  var p = this.parent.parent.parent.parent.parent.parent.parent.parent.parent;
  var u = _getViewParams().CALL_URN;

  // setup cache array
  if(typeof p.$_.env.cache == 'undefined') p.$_.env.cache = {};
  if(typeof p.$_.env.cache[u] == 'undefined') p.$_.env.cache[u] = [];

  if(typeof p.$_.env.cache[u][name] == "function") {
    console.log("_envCache : illegal property name '" + name + "'");
  }

  // read stored value else returns an undefined object
  if(typeof value == 'undefined') {
    if(typeof p.$_.env.cache[u][name] == 'undefined') return undefined;
    else return p.$_.env.cache[u][name];
  }

  else if (value == null) {
    delete p.$_.env.cache[u][name];
  }
  // set the value
  else {
    p.$_.env.cache[u][name] = value;
  }
}


/* Client side framework initialization (MAIN) */
$_.bindEvent(window, "load", function()
{
  /* force document id (used as mnenonic reference and as namespace) */
  document.body.id='root';

  /* initialize root namespace container */
  $_.webgets.root=[];

  /* launch initialization (page parsing) starting from 'body' TAG */
  $_._wInit(document.body,{childWebgets:[]},'root');

  /* binds enqueued events in the 'root' namespace */
  $_._flushBinds('root');

  /* client side flush event only if a linked library exists */
  var r = $_.webgets.root, j = $_.js.reg;
  for(var s in r){
    if(j[r[s].wid]) j[r[s].wid].fs(r[s]);
  }

  /* dispatch 'ready' event for the body TAG (AKA 'root webget') */
  /* TODO : seems not follows the global rules for event dispatching */
  if(typeof(document.body.ready) == 'object')
    document.body.dispatchEvent(document.body.ready);
});

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
