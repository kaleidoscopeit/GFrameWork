/* tipical parameters :

- curve type ( sine,square, ecc... )

sine :

1	- curve resolution ( in steps per period)
2	- amplitude max ( from 0 to 1 - bias)
3	- bias ( from 0 to 1 )
4	- start point ( from -180° to 180 degrees )

5	- current step

square

1	- curve resolution ( in steps per period)
2	- amplitude max ( from 0 to 1 - bias)
3	- bias ( from 0 to 1 )
4	- start step ( from 0 to an arbitrary first step )
5	- duty cicle ( from 0(%) to 100(%); default 50;)

6	- current step

primitive curves :

sine
square
triangular

syntax :

resource = $.js.curve.create([curvetype,param1,param2,parm...]);

parameters :
 
	- curve type
	- assorted curve type dependant parameters


value = $.js.curve.step(resource); */
$_.js.curve={
	curve:Array(),
	create:function(c){return this.curve.push(c.concat([0+c[4]]))-1;},
	step:function(r){
		switch(this.curve[r][0]){
			case 'sine':return this.sine(r);
			case 'square':return this.square(r);
		}
		return 0;
	},
	sine:function(r){with(this){
		y=Math.sin(((curve[r][5]+curve[r][4])*(360/curve[r][1]))*0.017453293)*curve[r][2]+curve[r][3];
		curve[r][5]++;
		return y;
	}},
	square:function(r){
		c=this.curve[r];
		duty=c[5]*c[1]/100;
		if(c[5]<50) duty=Math.ceil(duty);
		if(c[5]>50) duty=Math.floor(duty);		
		y=c[2]+c[3]
		y=c[6]<duty?y:-y;
		c[6]>c[1]?c[6]=0:c[6]++;
		return y;
	}

};
