$_.js.reg['0310']={
	a:['rowheight'],
	f:['ready'],
	b:function(n){
    n.populate = function(rs){
      if(rs.length==null & $_.count(rs) == 0)return false;
      //n.clear();
      $_.each(rs,function(row,i){
        n.current_record = row;
        $_.each(n.nextSibling.childNodes,function(elm,ii){
          var nelm=elm.cloneNode(true);          
          n.appendChild(nelm);
          nelm.style.display="block";
          nelm.style.width="100%";
          nelm.style.height = n.rowheight;
        })
      });

      n.wbgidx = $_.getChildWebgets(n);

      $_.each(n.wbgidx, function(elm,i){
        if(typeof $_.js.reg[elm.wid]!='undefined'){
          var r=$_.js.reg[elm.wid];
          for(var a in r.a)elm[r.a[a]]=$_.gea(elm,r.a[a]);
          for(var f in r.f)elm[r.f[f]]=new Function($_.gea(elm,r.f[f])||"");
          r.b(elm);
        }

        if($_.js.reg[elm.wid]) $_.js.reg[elm.wid].fs(elm);
        if(elm.refresh)elm.refresh();
      });
    }
	},
	fs:function(n){
		n.ready();
	}
};