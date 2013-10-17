$_.js.reg['0400']={
	a:[],
	f:[],
	b:function(n){with(n){
  	n.months = Array('gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre');
  	n.days   = Array('lunedi','martedi','mercoledi','giovedi','venerdi','sabato','domenica');
  	n.date   = new Date();

		n.refresh = function() {
		  this.mont = this.date.getMonth();
		  this.year = this.date.getFullYear();
		  this.week = this.date.getWeek();
		  alert(this.week);
		},

    n.date.getWeek = function() {
      var onejan = new Date(this.getFullYear(),0,1);
      return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
    };
	}},
	
	fs:function(n){with(n){
		/* register sub webgets */
		var subw = ['month_label','month_next','month_prev','month_area'];
		subw.map(function(wbg){
		    if(typeof window[n.id][wbg]!='undefined')n[wbg]=window[n.id][wbg];
		});  	
    n.month_next.onclick = function(){alert('a');};
    n.refresh();
			
      
	}}
};