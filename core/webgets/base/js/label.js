$_.js.reg['0010']={
	a:['field','field_format'],
	f:['onchange'],
	b:function(n){with(n){
 		if(n.firstChild)
		if(n.firstChild.firstChild)n.sub=n.firstChild.firstChild;
		else n.sub=n;

		n.caption=function(c){with(n){
			if(c===undefined)return sub.textContent;
			sub.textContent=c;
			onchange();
		}};
		
		n.valign=function(a){with(n){

		}};

		n.halign=function(a){with(n){

		}};
		
		n.refresh=function(){
		  var field=n.field.split(','),fs=[];
		  $_.each(field,function(f,i){
		    f=f.split(':');
		    eval('var row='+f[0]+'.current_record');
		    fs.push(row[f[1]]);
		  });
		  
      $_.jsimport('system.phpjs.vsprintf');
      n.caption(vsprintf(n.field_format,fs));
		}
	}},
	fs:function(n){}
};