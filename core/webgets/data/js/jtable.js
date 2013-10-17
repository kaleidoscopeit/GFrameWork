$$.js.reg['0310']={
	a:['rowheight'],
	f:['ready'],
	b:function(n){
    n.populate = function(rs){
      n.clear();n.recordset=rs;
      if(rs.length==null & $$.count(rs) == 0)return false;

      $$.each(rs,function(row,i){
        n.current_record = row;
        $$.each(n.nextElementSibling.children,function(elm,ii){
          var nelm=elm.cloneNode(true);          

          n.appendChild(nelm);
          nelm.style.display="block";
          nelm.style.height = n.rowheight;
          nelm.index = i;
          
          $$.each($$.getPlainWebgets(nelm), function(elm,i){
            if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
            if(elm.refresh)elm.refresh();
          });
        });
      });
      
      return false;
    };

    n.clear = function(){
      while(n.children.length!=0)n.removeChild(n.children[0]);
    };
 
	},
	
	fs:function(n){
		n.dispatchEvent(n.ready);
	},
	
	getfields:function(f){
		if(f == null) return false;
		var field=f.split(','),fs=[];
		$$.each(field,function(f,i){
			f=f.split(':');
			eval('var row='+f[0]+'.current_record');
			fs.push(row[f[1]]);
		});
		
		return fs;
	}
};