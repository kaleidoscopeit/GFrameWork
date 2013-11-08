function startPan(id,or,e,A,B){
	// modifica l'evento in base al browser
	if(!e) e = window.event;
	
	panVars={
		ruler:document.getElementById(id),
		paneA:document.getElementById(A),
		paneB:document.getElementById(B),
		mainpane:document.getElementById(id+'Main'),	
		currX:e.clientX,
		currY:e.clientY	
	}

	with(panVars){
		panVars.paneAHeight=paneA.style.height.slice(0,paneA.style.height.length-1),
		panVars.paneAWidth=paneA.style.width.slice(0,paneA.style.width.length-1),

		panVars.paneBHeight=paneB.style.height.slice(0,paneB.style.height.length-1),
		panVars.paneBWidth=paneB.style.width.slice(0,paneB.style.width.length-1),
		
		panVars.paneTotalHeight = Number(paneAHeight)+Number(paneBHeight);
		panVars.paneTotalWidth = Number(paneAWidth)+Number(paneBWidth);
	
		panVars.stepV= (paneA.offsetHeight+paneB.offsetHeight)/paneTotalHeight;
		panVars.stepH= (paneA.offsetWidth+paneB.offsetWidth)/paneTotalWidth;

		document.body.onmouseup = function onmouseup() {
			this.onmousemove = null;
			panVars = '';
		};
		
		switch(or){
			case 'V' :	
			document.body.onmousemove = function onmousemove(event) {vertPan(event);};
			break;
			
			case 'H' :
				document.body.onmousemove = function onmousemove(event) {horrPan(event);};
			break;
		}
	}
}

function vertPan(e){
	with(panVars){
		panVars.AHeight = paneAHeight-0+Math.floor((e.clientY-currY)/stepV);
		panVars.BHeight = paneBHeight-0-Math.floor((e.clientY-currY)/stepV);
	
		AHeight >paneTotalHeight ? AHeight = paneTotalHeight : null;
		AHeight <0 ? AHeight = '0' : null;
	
		BHeight >paneTotalHeight ? BHeight = paneTotalHeight : null;
		BHeight <0 ? BHeight = '0' : null;
	 
		paneA.style.height = AHeight+'%';
		paneB.style.height = BHeight+'%';
		dummy.value= paneAHeight-0+Math.floor((e.clientY-currY)/stepV)+'%';
	}	
}

function horrPan(e){
	with(panVars){
		panVars.AWidth = paneAWidth-0+Math.floor((e.clientX-currX)/stepH);
		panVars.BWidth = paneBWidth-0-Math.floor((e.clientX-currX)/stepH);
	
		AWidth >paneTotalWidth ? AWidth = paneTotalWidth : null;
		AWidth <0 ? AWidth = '0' : null;
	
		BWidth >paneTotalWidth ? BWidth = paneTotalWidth : null;
		BWidth <0 ? BWidth = '0' : null;
	 
		paneA.style.width = AWidth+'%';
		paneB.style.width = BWidth+'%';
		dummy.value= paneAWidth-0+Math.floor((e.clientX-currX)/stepH)+'%'+'   '+ paneTotalWidth;
	}	
}