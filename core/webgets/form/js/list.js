$_.js.reg['02B0']={
  a:[],
  f:[],
  b:function(n){
    n=n.firstChild;
    with(n){
      
    n.addEventListener('change', function(){
      var opt=this.copy(),i;
      this.selected=[];
      for(i in opt) this.selected.push(opt[i].value)
    }, false),
    
    n.copy = function(){
      var out=[],opt=this.options,i;
      this.values=[];
      this.captions=[];
      for(i in opt) {
        this.values.push(opt[i].value);
        this.captions.push(opt[i].text);
        if(opt[i].selected) out.push(opt[i]);}
      return out;
    },

    n.cut = function(){
      var out=[],opt=this.copy(),i;
      while(opt[0]) {
        this.options[opt[0].index] = null;
        out.push(opt.shift())
      }
      return out;
    },

    n.paste = function(o){
      while(o[0]) this.add(o.shift());
    },

    n.clear = function(){
      while(this.length!=0)this.remove(0);
    },

    n.populate = function(v){
      if(v.length==null)return false;
      n.clear();
      $_.tmp.n=n;
      $_.each(v,function(v,i){$_.tmp.n.items_insert(v[0],(v[1]?v[1]:v[0]))
      })
    },

    n.sort = function(){
      var out=[],i,opt=this.options;
      for(i=0;i<opt.length;i++) out.push(opt[i]);
      out.sort(function(x,y) {
        var a=x.text,b=y.text,z=0;
        if (a>b) z=1;
        if (a<b) z=-1;
        return z;
      });
      this.clear();
      this.paste(out);       
    }
  }},
  
  fs:function(n){
  }
};