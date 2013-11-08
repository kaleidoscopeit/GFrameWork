wbt.hid=function(wb){with(this){
	/* Se il webget specificato non esiste annulla con un errore */
	if(!cwb){alert('<?php echo $lc_msg['frmed_wbtman_1'];?>');return false};
	/* Se il webget selezionato è la radice del documento annulla con un messaggio */
	if(!cwb.prn.prn){alert('<?php echo $lc_msg['frmed_wbtvis_0'];?>');return false}
	
	if(!wb&&cwb)wb=cwb;
	
	if(wb.hid){wb.box.style.display=null;wb.hid=0;}
	else {wb.box.style.display='none';wb.hid=1;}
	window.opener.wman.cfnc('dct.up();','','topan');
}};

wbt.sha=function(r,s,i){
	if(!r){r=edt.struct['root']['en0'];i=1}
	r.box.style.display=null;
	r.hid=0;
	for(s in r){if(s.slice(0,2)=='en')wbt.sha(r[s])}
	if(i==1)window.opener.wman.cfnc('dct.up();','','topan');
};