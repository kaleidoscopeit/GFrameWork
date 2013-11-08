<table cellpadding="0" cellspacing="0" border="0" style="height: 24px;">
<tr style="height: 24px; "><td>
<img src="imges/tob-new.png" style="padding:2px;position:relative;height:24px;width:24px; border:1px outset;background-color:#EBEBEB;" title="Nuovo"
						onmousedown="this.style.borderStyle='inset';" onmouseup="this.style.borderStyle='outset';">
</td><td>
<img src="imges/tob-open.png" style="padding:2px;position:relative;height:24px;width:24px; border:1px outset;background-color:#EBEBEB;" title="Apri"
						onmousedown="this.style.borderStyle='inset';" onmouseup="this.style.borderStyle='outset';">
</td><td>
<img src="imges/tob-save.png" style="padding:2px;position:relative;height:24px;width:24px; border:1px outset;background-color:#EBEBEB;" title="Salva"
						onmousedown="this.style.borderStyle='inset';" onmouseup="this.style.borderStyle='outset';" onclick="sendData(CONF.siteroot+'/MWE-IDE/frmsv.php',{url:currArea.url,data:pject_buildXML()});">
</td><td>
<img src="imges/tob-prev.png" style="padding:2px;position:relative;height:24px;width:24px; border:1px outset;background-color:#EBEBEB;" title="Anteprima"
						onmousedown="this.style.borderStyle='inset';" onmouseup="this.style.borderStyle='outset';" onclick="window.open(CONF.siteroot+'/engine.php?CP='+currArea.url.slice(6,currArea.url.length-4),'preview');">
</td><td>

</td><td>

</td><td>

</td><td>

</td><td>

</td><td>

</td></tr>
</table>