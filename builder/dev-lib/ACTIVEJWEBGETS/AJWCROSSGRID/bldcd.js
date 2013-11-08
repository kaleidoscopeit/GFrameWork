{
/* AJWCROSSGRID */
	this.create=function(vtree,defaults){
		/* se viene passato solo un array co X e Y si considera come un posizionamento generico con i valori di base */
		if(!isNaN(defaults[0]))defaults = {TAGSPCS:{},GEOMETRY:this.X+'px,'+this.Y+'px,50px,15px',STYLE:''};

		defaults.TAGSPCS.FAMILY = 'ACTIVEJWEBGETS';
		defaults.TAGSPCS.TYPE = 'AJWCROSSGRID';
		
		this.box = document.createElement('div');
		this.box.id = defaults.TAGTYPE;
		this.box['vtree'] = vtree;
		this.box.style.cssText = 	"border:1px solid lightgrey;position:absolute;width:10px;heigth:10px;";

		return {box:this.box,core:this.box,param:defaults};	
	};


	this.renewPrpty = function(){
		this.param.GEOMETRY[0] = this.obj.style.left;
		this.param.GEOMETRY[1] = this.obj.style.top;
	};
}