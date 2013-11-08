{
/* VLAYOUT */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'VLAYOUT',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:'VLAYROW',CANDRAG:false};
	
	this.create=function(vtree,prpty){with(this){			
		this.box=dcr('div');
		box['vtree']=vtree;
		box.TYPE=TAGSPCS.TYPE;
		box.style.cssText='overflow:visible;position:absolute;width:100%;height:100%;';
		
		this.dum=dcr('div');box.appendChild(dum);
		dum.style.cssText='overflow:visible;position:absolute;width:100%;height:100%;';
		
		this.table=dcr('table');box.appendChild(table);
		table.style.cssText='position:absolute;width:100%;height:100%;';
		table.border=0;
		table.cellPadding=0;
		table.cellSpacing=0;

		this.core=dcr('tbody');table.appendChild(core);
		return {box:box,core:core,dum:dum,param:prpty};
	}};
}