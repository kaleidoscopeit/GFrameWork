$$.js.reg['0310']={
  a:['filling',
     'rowHeight',
     'cellsByRow',
     'cellSize'],
  f:['ready'],
  b:function(n){
    n.dataArea = n.children[1];
    n.rsObj = [];
    
    n.populate = function(rs){
      // Make the decision pattern
      var dPtCXR = "0",
          dPtCS = "0",
          realCellSize;
      if(n.cellsByRow != null) dPtCXR = "1";
      if(n.cellSize != null)   dPtCS  = "1";

      switch(dPtCXR+dPtCS){
        case "00":
          realCellSize = "";
          break;
        case "10":
          realCellSize = Math.round(10000/n.cellsByRow)/100 + "%";
          break;
        case "01":
          realCellSize = n.cellSize;
          break;
        case "11":
          realCellSize = n.cellSize + "px";
          n.dataArea.style.width = n.cellsByRow*n.cellSize + "px";
          break;      
      }
      
      n.clear();n.recordSet=rs;

      if(rs.length==null & $$.count(rs) == 0)return false;

      $$.each(rs,function(row,i){
        i=parseInt(i);
        n.current_record = row;
        $$.each(n.nextElementSibling.children,function(elm,ii){
          n.rsObj[i] = elm.cloneNode(true);          

          n.dataArea.appendChild(n.rsObj[i]);
          n.rsObj[i].style.display = "block";
          n.rsObj[i].style.height = n.rowHeight + "px";
          n.rsObj[i].style.width = realCellSize;
          n.rsObj[i].index = i;
            
          $$.each($$.getPlainWebgets(n.rsObj[i]), function(elm,i){
            if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
            if(elm.refresh)elm.refresh();
          });
        });
      });
      
      return false;
    };

    n.clear = function(){
      n.dataArea.innerHTML = '';return;
      while(n.dataArea.children.length!=1)
        n.dataArea.innerHTML = '';
    };

    n.getExposedRecords = function(){
      var scroll = n.scrollTop,
          dAreaH = n.offsetHeight,
          stop = true,
          cXr = 0,
          rh = this.dataArea.children[0].offsetHeight,
          vr = Math.ceil(dAreaH/rh),
          fvr = Math.floor(scroll/rh),
          lvr = vr+fvr;
      
      while(stop){
        if(this.dataArea.children[cXr] == 'undefined') break;
        if(this.dataArea.children[cXr].offsetTop > 0) break;
        cXr++;
      }

      // Calculate the first and the last visible record
      var fvrec = fvr*cXr;
      var lvrec = lvr*cXr-1;

      // Calculate exposed records plus extra boundaries 
      // by default they are about 1 page before and after
      fvrec = ((fvrec-vr*cXr)<0 ? 0 : fvrec-vr*cXr);
      lvrec = lvrec+vr*cXr;
      
      return Array(fvrec,lvrec);
      
    };
    
    n.getNewlyExposedRecords = function(){
      var exp = this.getExposedRecords();
      var nexr = [], i, und = false;
 
      for(i=exp[0];i<exp[1];i++){

        if(typeof this.recordSet[i] == 'undefined' && und == false){
           nexr.push(i);
          und = true; 
        }
        
        else if(typeof this.recordSet[i] != 'undefined' && und == true){
          nexr.push(i);
          und = false;
        }
      }      
      
      if(und == true) nexr.push(i);
      
      alert(nexr);
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