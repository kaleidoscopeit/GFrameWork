$_.js.reg['0400']={
  a:[
    'spacing',
    'day_names',
    'month_names',
    'first_day'
  ],
  f:['click'],
  b:function(n){with(n){
    n.monthNames = n.month_names.split(',');
		n.monthNamesMin = [];
		n.monthNamesShort = [];
		n.dayNames = n.day_names.split(',');
		n.dayNamesMin = [];
		n.dayNamesShort = [];

		n.dayNames.map(function(i){
			n.dayNamesMin.push(i.substr(0,2));
			n.dayNamesShort.push(i.substr(0,3));
		});

		n.monthNames.map(function(i){
			n.monthNamesMin.push(i.substr(0,2));
			n.monthNamesShort.push(i.substr(0,3));
		});

    n.date   = new Date();

		/* fill the various areas an hook the elements */
		n.fillUp = function() {
			n.days = [];
			n.weeks = [];
			var row=0,col=0;
			/* draws day heads cells */
			for(var i in n.dayNamesShort){
				var nn = n.makeCell(weekday_cell);
				with(nn.style){
					width  = (100/7)+"%";
					left   = (100/7)*i+"%";
				}
				nn.caption(n.dayNamesShort[i]);
			}

			while(row<6) {
				/* draw current week cell */
				var nn = n.makeCell(week_cell);
				n.weeks[row] = nn;
				n.days[row] = [];
				with(nn.style){
					height = 100/6+"%";
					top    = 100/6*row+"%";
				}

				/* draw days cells */
				while(col<7) {
					var nn = n.makeCell(day_cell);
					n.days[row][col] = nn;

					with(nn.style){
						height = 100/6+"%";
						width  = 100/7+"%";
						top    = (100/6*row)+"%";
						left   = (100/7*col)+"%";
					}

          $_.bindEvent(nn,'click',function(){
            switch(this.getAttribute('daytype')){
              case 'offday' :
                n.setMonth(n.getMonth() + (this.offsetTop == 0 ? -1 : 1));
                break;

              default :
                n.value = new Date(n.getFullYear(),
                                   n.getMonth(),
                                   this.caption(),
                                   12); // avoid timezone issues
            }
          });
					col++;
				}
				row++,col=0;
			}
		}

    n.refresh = function() {
      var row=0,col=0,did=0,sfx='',mad=n.getMonthAreaDays()
			var tdy = new Date(),tdi=0,fmw=this.getMonthFirstWeek();

			if(tdy.getMonth() == n.getMonth()
				&& tdy.getYear() == n.getYear())
					tdi = 1;

			tdy = tdy.getDate();

			if(mad[0] > 10) sfx = 'offday';
			month_label.caption(monthNames[getMonth()]  + " " +  getFullYear());

      while(row<6) {
        /* draw week cell */
        var w = row + fmw == 0 ? 52 : row + fmw;
				n.weeks[row].caption(w == 53 ? 1 : w);

        /* draw days cells */
        while(col<7) {
          if(typeof mad[did-1] != "undefined")
            if(mad[did-1]>mad[did])	sfx = (sfx=='' ? 'offday' : '');
					n.days[row][col].caption(mad[did]);
					if(tdi && tdy == mad[did])
						n.days[row][col].setAttribute('daytype','today');
					else
						n.days[row][col].setAttribute('daytype',sfx);

          col++,did++;
        }
        row++,col=0;
      }
    };

		n.setMonth = function(m) {
			n.date.setMonth(m);
			n.refresh();
		};

    n.getMonth = function() {return this.date.getMonth();};
    n.getFullYear = function() {return this.date.getFullYear();};
		n.getYear = function() {return this.date.getYear();};

    n.getWeek = function() {
      var onejan = new Date(this.getFullYear(),0,1);
      return Math.ceil((this - onejan) / 604800000);
    };

    n.getMonthFirstWeek = function() {
      // ys = year start, fom = first of month, wn = week number
      var ys = new Date(this.getFullYear(),0,1,0,0,0,0);
      var fom = new Date(this.getFullYear(),this.getMonth(),1,0,0,0,0);
      fom.setDate(fom.getDate() + 4 - (fom.getDay()||7));
      var wn = Math.ceil(( ( (fom - ys) / 86400000) + 1)/7);
      return wn;
    };

    n.getMonthFirstWeekDay = function() {
      var f = new Date(this.getFullYear(),this.getMonth(),1);
      f = (f.getDay() == 0 ? 6 : f.getDay()-1);
      return f;
    };

    n.getMonthAreaDays = function(){
      var mfwd = n.getMonthFirstWeekDay(),dayNames=new Array();
      for(var x=1;x<43;x++){
        var temp = new Date(this.getFullYear(),this.getMonth(),-mfwd+x);
				dayNames.push(temp.getDate());
      }

      return dayNames;
    };

		n.makeCell = function(m){
			var nn=m.cloneNode(true);
			nn.wid=m.getAttribute('wid');
			if($_._wAttachJs(nn)) $_.js.reg[nn.wid].fs(nn);
			nn.style.visibility = "";
			m.parentNode.appendChild(nn);
			return nn;
		}
  }},

  fs:function(n){with(n){
    /* grab and hide sub model webgets */
		['week_cell',
		 'day_cell',
		 'weekday_cell',
		 'month_label',
		 'month_prev',
		 'month_next']
		.map(function(wbg) {n[wbg] = _w(n.id + '_' + wbg);});

		['week_cell', 'day_cell', 'weekday_cell'].map(function(wbg) {
			n[wbg].style.visibility="hidden";
		});

		$_.bindEvent(n.month_next,'click',function(){
			n.setMonth(n.getMonth()+1)
		});

		$_.bindEvent(n.month_prev,'click',function(){
			n.setMonth(n.getMonth()-1)
		});

    n.fillUp();
		n.refresh();
  }}
};
