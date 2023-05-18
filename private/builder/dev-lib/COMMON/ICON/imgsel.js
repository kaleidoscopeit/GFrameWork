/*

 	wow		:	scorciatoia all'opener
 	wpr		:	scorciatoia	a window.opener.wman.gwnd('prpty').pty
 	wid		:	id della finestra origine del webget
 	wbt		:	webget remoto nella finestra del form selezionato
 	pn			:	nome della proprietà
 	pv			:	valore originale della proprietà
	cwb		:	collegamento al webget da modificare
*/

function str(rot){
	window.resizeTo(400,320);
	wop=rfw.wman;
	wpr=wop.gwnd('topan').pty;
	oid=wpr.wid;
	pn=wpr.cfld.prpid;
	pv=wpr.cfld.value;	 			
	cwb=wpr.cwb;
	
	if(!wop.wnd[oid].document){
		alert('Il Form che contiene il Webget è attualmente chiuso.');
		self.close();
	}
	if(!cwb){
		alert('Il Webget a cui si fa riferimento non è accessibile! \nProbabilmente è stato eliminato.');
		self.close();
	}

	size=wop.wnd[oid].wbt.cwb.param.SIZE;

	/* carica il menu' ad albero */
	pjnav('nav');
	nav.uri='/themes/default/icons/'+size+'/normal/';
	nav.ock=function(){
		if(this.itemMime!='inode')image.src='../../'+rot+'/themes/default/icons/'+size+'/normal/'+this.itemName;
		pst.value=this.itemName;
	};
	nav.odck=function(){
		wop.wnd[oid].wbt.upy('SRC',pst.value,cwb);
		wop.wnd[oid].wbt.upb()
	};
	nav.lbl='Images';
	nav.style.cssText="left:0px;top:0px;width:100%;height:100%;background-color: white;";
	nav.lmi.png='';
	dge('dp').appendChild(nav);
	nav.update();
	
	image=dge('image');
	pst=dge('pst');
	/* riporta il valore attuale, se presente.*/
	pst.value=wop.wnd[oid].wbt.cwb.param.SRC;
	prv=pst.value;
	image.src='../../'+rot+'/themes/default/icons/'+size+'/normal/'+pst.value;
}

function ups(){
	wop.wnd[oid].wbt.upy('SRC',pst.value,cwb);
	wop.wnd[oid].wbt.upb();
	self.close();
}
