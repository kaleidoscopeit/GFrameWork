$$.js.reg['0310']={
  a:['filling',
     'rowHeight',
     'cellsByRow',
     'cellSize',
     'fillBoundary'],
  f:['define','flush','scrollend','datarequired'],
  
  b:function(n){
    n.dataArea = n.children[1];
    n.rsObj = [];
    n.recordSet = [];
    
    n.fillData = function(rs,range){

      if(typeof rs.maxlength != 'undefined') {
        n.dataSouceLength = rs.maxlength;
        delete(rs.maxlength);
      }

      n.calcFillingParams();
      if(typeof n.finalRowSize != 'undefined') {
        n.dataArea.style.width = n.finalRowSize + "px";
      }
      
      if(n.filling == "p" && range != null) {
        
        var i = range[0];
        
        while(i<range[1]&&i<n.dataSouceLength){
          n.recordSet[i] = rs[i-range[0]];  
          n.current_record = n.recordSet[i];
          $$.each(n.nextElementSibling.children,function(elm,ii){
            var obj = elm.cloneNode(true);          
            
            n.dataArea.appendChild(obj);
            obj.style.display = "block";
            obj.style.height = n.rowHeight + "px";
            obj.style.width = n.finalCellSize + n.cellSizing;
            obj.index = i;

            $$.each($$.getPlainWebgets(obj), function(elm,i){
              if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
              if(elm.refresh)elm.refresh();

            });
            
            n.rsObj[i] = obj;
          }); 
                   
          i++;
        }        
      }
      
      else {
        n.clear();
        n.recordSet = rs;
        $$.each(n.recordSet,function(row,i){
          i=parseInt(i);
          n.current_record = row;
          $$.each(n.nextElementSibling.children,function(elm,ii){
            var obj = elm.cloneNode(true);          
            
            n.dataArea.appendChild(obj);
            obj.style.display = "block";
            obj.style.height = n.rowHeight + "px";
            obj.style.width = n.finalCellSize + n.cellSizing;
            obj.index = i;
            
            $$.each($$.getPlainWebgets(obj), function(elm,i){
              if($$._wAttachJs(elm)) $$.js.reg[elm.wid].fs(elm);
              if(elm.refresh)elm.refresh();
            });
            
            n.rsObj[i] = obj;
          });
        });
      }
      
      n.dReq = false;
      return true;
    };

    n.calcFillingParams = function(){
      // Make the decision pattern
      var dPtCXR = "0",
          dPtCS = "0";

      if(n.cellsByRow != null) dPtCXR = "1";
      if(n.cellSize != null)   dPtCS  = "1";

      switch(dPtCXR+dPtCS){
        case "00":
          n.finalCellSize = "";
          n.cellSizing = "";
          break;
        case "10":
          n.finalCellSize = Math.round(10000/n.cellsByRow)/100 ;
          n.cellSizing = "%";
          break;
        case "01":
          // Modificato il 2014-07-22 da 
          // n.finalCellSize = n.cellSize;
          // Non segnato in documentazione : vedere le incongruenze
          n.finalCellSize = n.finalRowSize = n.cellSize;
          
          
          n.cellSizing = "px";
          break;
        case "11":
          n.finalCellSize = n.cellSize;
          n.finalRowSize = n.cellsByRow*n.cellSize;
          n.cellSizing = "px";
          break;      
      }      
    };
    
    n.clear = function(){
      n.dataArea.innerHTML = '';
      n.recordSet = [];
      return;
      while(n.dataArea.children.length!=1)
        n.dataArea.innerHTML = '';
    };

    n.getExposedRecords = function(){
      n.calcFillingParams();
      this.fillBoundary=this.fillBoundary||1;
       
      var scroll = n.scrollTop,
          dAreaH = n.offsetHeight,
          dAreaW = n.offsetWidth,
          stop = true,
          cXr = 0,
          rh = n.rowHeight,
          vr = Math.ceil(dAreaH/rh),
          fvr = Math.floor(scroll/rh),
          lvr = vr+fvr;

      if(n.cellsByRow != null) cXr = n.cellsByRow;
      else cXr = Math.floor(dAreaW/n.finalCellSize);
      

      // Calculate the first and the last visible record
      var fvrec = fvr*cXr;
      var lvrec = lvr*cXr-1;

      // Calculate exposed records plus extra boundaries 
      // by default they are about 1 page before and after
      fvrec = (fvrec-vr*(cXr*this.fillBoundary)<0 ?
        0 : fvrec-vr*(cXr*this.fillBoundary));
      lvrec = lvrec+vr*(cXr*this.fillBoundary);

      return Array(fvrec,lvrec);      
    };
    
    n.getNewlyExposedRecords = function(){
      var exp = this.getExposedRecords();
      var nexr = [], i, und = false;

      if(typeof this.recordSet != 'undefined') {
      
        for(i=exp[0];i<exp[1];i++){
  
          if(typeof this.recordSet[i] == 'undefined' && und == false){
            nexr.push(i);
            und = true; 
          }
          
          else if(typeof this.recordSet[i] != 'undefined' && und == true){
            nexr.push(i);
            und = false;
          }
        };
        
        if(und == true) nexr.push(i);
      }
      
      else nexr = exp;     
     
      exp = [];      
      while(nexr.length>1) exp.push(Array(nexr.shift(),nexr.shift()));
      return exp;
    };

    n.scrollEndDispatcher = function(){

      n.dispatchEvent(n.scrollend);
      if(n.filling=="p"){        
        var ner=n.getNewlyExposedRecords();
        if(ner.length>0 && n.dReq == false) {
          n.dReq = true;
          n.dispatchEvent(n.datarequired);
        }
      }
    };
    
    n.prefillExposedArea = function(){
      
    };
    
    n.dispatchEvent(n.define);
  },
  
  fs:function(n){
    $$.bindEvent(n, "scroll", function(){
      
      clearTimeout(this.scrollTimeout);
      this.scrollTimeout = setTimeout(n.scrollEndDispatcher, 250);
            
    });

    n.dispatchEvent(n.flush);
    n.prefillExposedArea();
  },
  
  getfields:function(f){
    if(f === null) return false;
    var field=f.split(','),fs=[];
    $$.each(field,function(f,i){
      f=f.split(':');
      eval('var row='+f[0]+'.current_record');
//      console.log(f);
      fs.push(row[f[1]]);
    });

    return fs;
  }
};