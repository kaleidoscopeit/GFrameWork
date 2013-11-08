startupFunct.layman = function(){
	VARS.wkspTop = 34;
	VARS.wkspLeft = document.getElementById('MainPaneB').offsetLeft;
}		


function laymn_startPan(id,or,e,A,B){
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	
	PANV={
		ruler:document.getElementById(id),
		paneA:document.getElementById(A),
		paneB:document.getElementById(B),
		mainpane:document.getElementById(id+'Main'),	
		currX:e.clientX,
		currY:e.clientY	
	}

	with(PANV){
		PANV.paneAHeight=paneA.style.height.slice(0,paneA.style.height.length-1),
		PANV.paneAWidth=paneA.style.width.slice(0,paneA.style.width.length-1),

		PANV.paneBHeight=paneB.style.height.slice(0,paneB.style.height.length-1),
		PANV.paneBWidth=paneB.style.width.slice(0,paneB.style.width.length-1),
		
		PANV.paneTotalHeight = Number(paneAHeight)+Number(paneBHeight);
		PANV.paneTotalWidth = Number(paneAWidth)+Number(paneBWidth);
	
		PANV.stepV= (paneA.offsetHeight+paneB.offsetHeight)/paneTotalHeight;
		PANV.stepH= (paneA.offsetWidth+paneB.offsetWidth)/paneTotalWidth;

		document.body.onmouseup = function onmouseup() {
			this.onmousemove = null;
			PANV = null;
		};
		
		switch(or){
			case 'V' :	
			document.body.onmousemove = function onmousemove(event) {laymn_vertPan(event);};
			break;
			
			case 'H' :
				document.body.onmousemove = function onmousemove(event) {laymn_horrPan(event);};
			break;
		}
	}
}

function laymn_vertPan(e){
	with(PANV){
		PANV.AHeight = paneAHeight-0+Math.floor((e.clientY-currY)/stepV);
		PANV.BHeight = paneBHeight-0-Math.floor((e.clientY-currY)/stepV);
	
		AHeight >paneTotalHeight ? AHeight = paneTotalHeight : null;
		AHeight <0 ? AHeight = '0' : null;
	
		BHeight >paneTotalHeight ? BHeight = paneTotalHeight : null;
		BHeight <0 ? BHeight = '0' : null;
	 
		paneA.style.height = AHeight+'%';
		paneB.style.height = BHeight+'%';
		OBJS.dummy.value= paneAHeight-0+Math.floor((e.clientY-currY)/stepV)+'%';
	}	
}

function laymn_horrPan(e){
	with(PANV){
		PANV.AWidth = paneAWidth-0+Math.floor((e.clientX-currX)/stepH);
		PANV.BWidth = paneBWidth-0-Math.floor((e.clientX-currX)/stepH);
	
		AWidth >paneTotalWidth ? AWidth = paneTotalWidth : null;
		AWidth <0 ? AWidth = '0' : null;
	
		BWidth >paneTotalWidth ? BWidth = paneTotalWidth : null;
		BWidth <0 ? BWidth = '0' : null;
	 
		paneA.style.width = AWidth+'%';
		paneB.style.width = BWidth+'%';
		OBJS.dummy.value= paneAWidth-0+Math.floor((e.clientX-currX)/stepH)+'%'+'   '+ paneTotalWidth;
		// aggiorna la posizione assoluta sinistra dello spazio di lavoro		
		VARS.wkspLeft = document.getElementById('MainPaneB').offsetLeft;
	}	
}