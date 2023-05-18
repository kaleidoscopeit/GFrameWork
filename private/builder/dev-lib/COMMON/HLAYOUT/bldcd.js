{
/* HLAYOUT */
	/* parametri speciali per l'interazione con l'editor */
	this.TAGSPCS={TYPE:'HLAYOUT',TARGET:'ALL',FAMILY:'COMMON',ACCEPT:'HLAYCOL',CANDRAG:false};
		
	this.create=function(vtree,prpty){with(this){		
		this.box=dcr('div');
		box['vtree']=vtree;
		box.TYPE=TAGSPCS.TYPE;
		box.style.cssText="position:absolute;width:100%;height:100%;";

		this.table=dcr('table');box.appendChild(table);
		table.style.cssText="width:100%;height:100%";
		table.border=0;
		table.cellPadding=0;
		table.cellSpacing=0;		

		this.tbody=dcr('tbody');

		table.appendChild(tbody);
		this.core=dcr('tr');
		tbody.appendChild(core);
		
		return {box:box,core:core,dum:core,param:prpty};
	}}
};