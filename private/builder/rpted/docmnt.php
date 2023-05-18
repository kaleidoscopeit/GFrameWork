<script type="text/JavaScript">
// funzioni locali di gestione dell'aspetto di questa sezione

// aggiunge allo script di avvio le funzioni locali
startupFunct.docmnt = function(){
	OBJS.docstrct = document.getElementById('docstrct');
	OBJS.docxtra = document.getElementById('docxtra');
}

	// gestore delle schede visualizzate
	// -----------------------------------------------
	function docmt_dcmSwitcher(page,button){
		with(OBJS){
			docstrct.style.visibility='hidden';
			docxtra.style.visibility='hidden';
			
			button.parentNode.parentNode.childNodes[1].firstChild.style.backgroundColor = '#E5E5E5';
			button.parentNode.parentNode.childNodes[3].firstChild.style.backgroundColor = '#E5E5E5';
			button.style.backgroundColor = 'lightgrey';
			
			switch(page){
				case 'docstrct' :
					docstrct.style.visibility='visible';
					
				break;

				case 'docxtra' :
					docxtra.style.visibility='visible';
				break;
			}
		}	
	}
</script>

<div style="overflow:hidden;height:100%;width:100%;">
	<table cellpadding="1" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;"><tr><td valign="top" align="left" style="" nowrap>
		<div style="overflow:hidden; width:100%;">
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
<tr>
<td><input type="button" value="Struttura" onclick="docmt_dcmSwitcher('docstrct',this);" style="border-width:1px;width:100%;height:20px;"></td>
<td><input type="button" value="Metodi" onclick="docmt_dcmSwitcher('docxtra',this);" style="border-width:1px;width:100%;height:20px;"></td>
</tr>
</table>
		</div>
	</td></tr><tr><td valign="top" style="height:100%;">
	<div style="overflow:auto;position:relative;height:100%;background-color:#E5E5E5;">
	<div id="docstrct" style="position:absolute;left:0px;top:0px;">
		<div id="docnavhl" style="position:absolute;width:100%;height:20px;top:-20px;background-color:lightgray;"></div>
	</div>
	<div id="docxtra" style="position:absolute;left:0px;top:0px;width:100%;visibility:hidden;">
		objct
	</div>
	</div></td></tr></table>
</div>