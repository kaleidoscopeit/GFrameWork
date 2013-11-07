$_.js.reg['0400']={
	a:['spacing'],
	f:[],
	b:function(n){with(n){
  	n.months = Array('gennaio','febbraio','marzo','aprile','maggio','giugno',
  	 'luglio','agosto','settembre','ottobre','novembre','dicembre');
  	n.days   = Array('lunedi','martedi','mercoledi','giovedi','venerdi',
  	 'sabato','domenica');
    n.sdays  = Array('lun','mar','mer','gio','ven','sab','dom');
  	n.date   = new Date();

		n.refresh = function() {
      var vdiv=(100/6),hdiv=(100/7),
          vspc=1/6*this.spacing,hspc=1/7*this.spacing,
          spc=this.spacing;

      /* draw week cells */
      for(var col in n.sdays){
        var nn=n.weekday_cell.cloneNode(true),
            pp=n.weekday_cell.parentNode;
        nn.wid=nn.getAttribute('wid');
        if($$._wAttachJs(nn)) $$.js.reg[nn.wid].fs(nn);
        pp.appendChild(nn);
        with(nn.style){
          width  = (hdiv-vspc)+"%";
          left   = (hdiv*col+hspc/2)+"%";
          visibility = "visible";
        }
        nn.caption(n.sdays[col]);
      }
       
      var row=0,fmwk=this.getMonthFirstWeek(),act=0,
          bclass=n.day_cell.getAttribute('dyn_class');
      while(row<6) {    
        /* draw current week cell */  
        var nn=n.week_cell.cloneNode(true),
            pp=n.week_cell.parentNode;
        nn.wid=nn.getAttribute('wid');
        if($$._wAttachJs(nn)) $$.js.reg[nn.wid].fs(nn);
        pp.appendChild(nn);
        with(nn.style){
          height = (vdiv-vspc)+"%";
          top    = (vdiv*row+vspc/2)+"%";
          visibility = "visible";
        }  

        nn.caption(row + this.getMonthFirstWeek());
        
        /* draw day cells */
        var col=0,mad=n.getMonthAreaDays();
        while(col<7) {
          var nn=n.day_cell.cloneNode(true),
              pp=n.day_cell.parentNode;
          nn.wid=nn.getAttribute('wid');
          if($$._wAttachJs(nn)) $$.js.reg[nn.wid].fs(nn);
          pp.appendChild(nn);          
          with(nn.style){
            height = (vdiv-vspc)+"%";
            width  = (hdiv-hspc)+"%";
            top    = (vdiv*row+vspc/2)+"%";
            left   = (hdiv*col+hspc/2)+"%";
            visibility = "visible";
          }
          if(cday>mad[row*7+col])act=!act;
          if(!act)$$.addClass(nn,bclass+"_disabled");
          nn.caption(mad[row*7+col]);          
          var cday=mad[row*7+col];
          col++;
        }        
        row++;        
      }
		},

    n.getMonth = function() {return this.date.getMonth();};
    n.getFullYear = function() {return this.date.getFullYear();};
    
    n.getWeek = function() {
      var onejan = new Date(this.getFullYear(),0,1);
      return Math.ceil((this - onejan) / 604800000);
    };
    
    n.getMonthFirstWeek = function() {
      var onejan = new Date(this.getFullYear(),0,1);    
      var firstofmonth = new Date(this.getFullYear(),this.getMonth(),0);
      return Math.ceil((firstofmonth - onejan) / 604800000);
    };

    n.getMonthFirstWeekDay = function() {
      var firstofmonth = new Date(this.getFullYear(),this.getMonth(),0);
      return firstofmonth.getDay();
    };
    
    n.getMonthAreaDays = function(){
      var mfwd = n.getMonthFirstWeekDay(),days=new Array();
      for(var x=1;x<43;x++){
        var temp = new Date(this.getFullYear(),this.getMonth(),-mfwd+x);
        days.push(temp.getDate());
      }
      
      return days;
    };
	}},
	
	fs:function(n){with(n){
		/* register sub webgets */
    var subw = [
      'week_cell',
      'day_cell',
      'weekday_cell'
    ];
      
    subw.map(function(wbg){
        if(typeof window[n.id][wbg]!='undefined')n[wbg]=window[n.id][wbg];
        n[wbg].style.visibility="hidden";
    });

    
       		
    n.refresh();
			
      
	}}
};