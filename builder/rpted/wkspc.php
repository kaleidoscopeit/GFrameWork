<div style="overflow:hidden;position:relative;height:100%;border:1px solid lightgrey;background-color:white;">
	<table cellpadding="0" cellspacing="0" border="0" style="height:100%;"><tr><td>
		<input type="button" value="&middot;" style="width:20px;height:20px;border:1px ridge gray;">
	</td><td  style="width:100%;">
		<div style="height:20px;width:100%;background-color:lightgrey; background-image: url('imges/hruler.png');">
			<div id="hpoint" style="position:relative;width:1px;height:100%;background:black;"></div>
		</div>
	</td></tr><tr valign="top"><td style="height:100%;">
		<div style="background-color: lightgrey; background-image: url('imges/vruler.png'); height: 100%; width: 20px; ">
			<div id="vpoint" style="position:relative;width:100%;height:1px;background:black;"></div>
		</div>
	</td><td style="height:100%;height:100%;">
		<div id="designspace" style="position:relative;overflow:auto;height:100%;width:100%;"  onmousemove="if(VARS.dragON==true){wbget_dragWbget(event);};if(VARS.reszON==true){wbget_reszWbget(event);};wkspc_pointerEffect(event);"></div>
	</td></tr><tr><td colspan="2">
		<div id="dspcSwitcher" style="height:20px;width:100%;background-color:lightgrey;border-top:1px solid;"></div>
	</td></tr></table>	
	<div id="grid" style="position:relative;left:0px;top:0px;width:100%;height:100%;" ></div>
	<div id="hilighter" style="position:relative;left:0px;top:0px;width:100%;height:100%;background-color:black;opacity:0.1;" ></div>
	<div id="floater"  style="position:absolute;left:0px;top:0px;width:100%;height:100%;visibility:hidden;" >
		<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid;width:100%;height:100%;"><tr>
			<td><div style="width:5px;height:5px;background-color:black;cursor:nw-resize;" onmousedown="if(VARS.reszEN==1)wbget_reszStart('NE',event);">&nbsp;</div></td><td width="100%"></td><td><div style="width:5px;height:5px;background-color:black;cursor:ne-resize;" onmousedown="if(VARS.reszEN==1)wbget_reszStart('NW',event);">&nbsp;</div></td></tr><tr>
			<td></td><td style="width:100%;height:100%;cursor:move;" ></td><td></td></tr><tr>
			<td><div style="width:5px;height:5px;background-color:black;cursor:sw-resize;" onmousedown="if(VARS.reszEN==1)wbget_reszStart('SE',event);">&nbsp;</div></td><td width="100%"></td><td><div style="width:5px;height:5px;background-color:black;cursor:se-resize;" onmousedown="if(VARS.reszEN==1)wbget_reszStart('SW',event);">&nbsp;</div></td>
		</tr></table>
	</div>
</div>

