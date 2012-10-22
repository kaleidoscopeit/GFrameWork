var $_={
	copyright:"(c)2005-2012 Gabriele Rossetti",licence:"GPL",env:{},lib:{},
	js:{reg:Array()},
	tmp:{},webgets:Array(),

	// webget client code initialization
	start:function(s,w){

		var t=document.getElementsByTagName('div');
		$_.tmp.wid_cache=[];
		$_.each(document.getElementsByTagName('div'),function(o,i,r,a,f){
			if(o.getAttribute('wid')!=undefined){
				o.wid=$_.gea(o,'wid');
				if(typeof $_.js.reg[o.wid]!='undefined'){
					r=$_.js.reg[o.wid];
					for(a in r.a)o[r.a[a]]=$_.gea(o,r.a[a]);
					for(f in r.f)o[r.f[f]]=new Function($_.gea(o,r.f[f]));
					$_.tmp.wid_cache.push(o);
					r.b(o);
			 	} 
			}			
		})
		
		for(s in this.lib)this.lib[s].construct();
		$_.onload();

		for(s in $_.tmp.wid_cache){
			$_.js.reg[$_.tmp.wid_cache[s].wid].fs($_.tmp.wid_cache[s]);
		}
		for(s in this.lib)this.lib[s].flush();

	},

	// grab webget
	gbw:function(l,w,sw,tw,cw){for(w in l){eval('if(!window.'+l[w]+')window.'+l[w]+'=this.gei(l[w]);')}},

	// space saving wrapper for common functions
	gea:function(n,a){return n.getAttribute(a);},
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
		l=(isNaN(o.length)?$_.count(o):o.length);
		for(var i=0;i<l;i++)f(o[i],i);
	},
	
	// import in the javascript context ($_.webgets) all webgets and glues them with the hierarchy relationship
	hcy:function(h){
		eval('this.hierarcy='+h);
		this.hcyi(Array('root'));
	},
	
	hcyi:function(s,ch,po,co,w){
		eval("ch=this.hierarcy['"+s.join("']['")+"'];");
		po=this.gei(s[s.length-1]);

		for(w in ch){
			if(w===0)alert(s[s.length-1]);
			co=this.webgets[w]=this.gei(w);
			if(po && co){
				co.parent=po;
				if(!po.childs)po.childs=Array();
				po.childs.push(co);
			}
			s.push(w);
			this.hcyi(s);
			s.pop()
		}
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
	call:function(m,b,h)
	{		
		this.jsimport('system.phpjs.unserialize');
		this.jsimport('system.phpjs.serialize');
		this.jsimport('system.phpjs.array_search');

		var x=this.xhr(),f,p='b='+serialize(b),c=1,i,o;

		// look-up for 'stack' flag in order to manage it locally 
		// avoiding unnecessary back and forth traffic. Sets f to 1
		// and remove that flag in the request
		if(h){
			h=h.split(',');
			if(f==array_search('stack',h))h.splice(f,1);
			h=h.join(',');
			p+=';h='+h;
		}		
		
		x.open('POST', '?call/'+m.replace(/\./g,'/') , false);
		x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		x.setRequestHeader("Content-length", p.length);
		x.setRequestHeader("Connection", "close");
		x.send(p);
		
		try{o=unserialize(x.responseText);}
		catch(e){alert('Parsing Call response failed : '+x.responseText);return false;}

		// if response is a string put that in the default subitem '0'
		for(var i in o[1])c++;if(c==1)o[1]={0:o[1]};

		// delete unwanted element if 'stack' flag is not set		
		if(!f)for(p in b)if(!o[1][p])delete b[p];
		
		// merges responses values
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
		catch(e){alert(xhr.responseText);er=1;throw('Cannot import '+l+' -> '+e+'; Response :'+xhr.responseText)}
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