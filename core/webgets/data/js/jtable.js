$_.js.reg['0310']={
	a:['rowheight'],
	f:['ready'],
	b:function(n){
    n.populate = function(rs){
      n.clear();n.recordset=rs;
      if(rs.length==null & $_.count(rs) == 0)return false;

      $_.each(rs,function(row,i){
        n.current_record = row;
        $_.each(n.nextSibling.childNodes,function(elm,ii){
          var nelm=elm.cloneNode(true);          
          n.appendChild(nelm);
          nelm.style.display="block";
          nelm.style.height = n.rowheight;
          nelm.index = i;
          
          var wbgidx = $_.getChildWebgets(nelm);
          wbgidx.unshift(nelm);

          $_.each(wbgidx, function(elm,i){
            if(typeof $_.js.reg[elm.wid]!='undefined'){
              var r=$_.js.reg[elm.wid];
              for(var a in r.a)elm[r.a[a]]=$_.gea(elm,r.a[a]);
              for(var f in r.f)elm[r.f[f]]=new Function($_.gea(elm,r.f[f])||"");
              r.b(elm);
            }
    
            if($_.js.reg[elm.wid]) $_.js.reg[elm.wid].fs(elm);
            if(elm.refresh)elm.refresh();
          });
        })
      });
    };

    n.clear = function(){
      while(n.childNodes.length!=0)n.removeChild(n.firstChild);
    }
 
	},
	fs:function(n){
		n.ready();
	},
	
	getfields:function(f){
		if(f == null) return false;
		var field=f.split(','),fs=[];
		$_.each(field,function(f,i){
			f=f.split(':');
			eval('var row='+f[0]+'.current_record');
			fs.push(row[f[1]]);
		});
		
		return fs;
	}
};