<?php
if(!$SG)die;
//$tkt=substr(microtime(),2,4);
$tkt='topan';
unset($SG['js'][$tkt]);
$SG['js'][$tkt]["jscript/layman.js"]=0;
$SG['js'][$tkt]["frmed/jscript/tbxchrg.js.php"]=0;
$SG['js'][$tkt]["frmed/jscript/tbxman.js"]=0;
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/channel.js"]=0;
$SG['js'][$tkt]["frmed/jscript/prpty.js"]=0;
$SG['js'][$tkt]["frmed/jscript/dctree.js"]=0;
?>
<html>
<head>
	<title><?php echo $lc_msg['frmed_topan'];?></title>
			<style>
			.a{border-width:1px;width:100%;height:20px;background-color:#E5E5E5;}
			.c{position:absolute;left:0px;top:0px;width:100%;visibility:hidden;}
			.widbutt{font: 12px arial,sans;}
			.widbutt:hover{background-color: lightblue;cursor:pointer;}
		</style>
</head>
<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
<script type="text/JavaScript" src="js.php"></script>
<body style="position:absolute;overflow:hidden;height: 100%;width:100%;margin:0px;background-color:#EBEBEB;" onload="start();pty.str();eval(wman.cbf);dct.st();">
<table cellpadding="0" style="position:absolute;width:100%;height:100%;"><tr ><td id="MainPaneA">
	<div style="overflow:auto;width:100%;height:100%;">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;background-color:#E5E5E5;"><tr><td valign="top" style="height:20px;">
		<select size="0" id="selector" onchange="tbx.switch(this.value);" style="width:100%;border:1px solid grey;"></select>
		</td></tr><tr><td valign="top" style="height:100%;">
		<div id="tobox" style="overflow:hidden;position:relative;height:100%;"></div>
		</td></tr></table>
	</div></td>
</tr><tr>
	<td style="cursor: s-resize;height:5px;padding-top:2px;" align="center" id="PaneT" onmousedown="layman.startPan('V',event,'MainPaneA','MainPaneB');return false;">
		<img src="imges/handle-h.png" onmousedown="return false;">
	</td>
</tr><tr>
	<td id="MainPaneB" style="height:30%"><div style="overflow:auto;width:100%;height:100%;">
	<div style="overflow:hidden;height:100%;width:100%;">
		<table cellpadding="1" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;"><tr><td valign="top" align="left" nowrap>
			<div style="overflow:hidden; width:100%;">
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
			<tr>
			<td><input id="ptyb" type="button" value="Propriet&agrave;" onclick="pty.switcher('pta',this);" class="a" style="background-color:lightgrey;"></td>
			<td><input id="evnb" type="button" value="Eventi" onclick="pty.switcher('eva',this);" class="a"></td>
			<td><input id="itcb" type="button" value="Interazione" onclick="pty.switcher('ita',this);" class="a"></td>
			<td><input id="rfnb" type="button" value="Riferimenti" onclick="pty.switcher('rfa',this);" class="a"></td>
			</tr>
		</table>
	</div>
	</td></tr><tr><td valign="top" style="height:100%;">
		<div style="overflow:auto;position:relative;height:100%;background-color:#EEEEEE;">
		<div id="ptya" class="c" style="visibility:visible;">&nbsp;</div>
		<div id="evna" class="c">&nbsp;</div>
		<div id="itca" class="c">&nbsp;</div>
		<div id="rfna" class="c">&nbsp;</div>
	</div></td></tr></table>
</div>
	</div></td>
</tr><tr>
	<td style="cursor: s-resize;height:5px;padding-top:2px;" align="center" id="PaneB" onmousedown="layman.startPan('V',event,'MainPaneB','MainPaneC');return false;">
		<img src="imges/handle-h.png" onmousedown="return false;">
	</td>
</tr><tr>
	<td id="MainPaneC" style="height:30%"><div style="overflow:auto;width:100%;height:100%;">
		<table cellpadding="1" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;"><tr><td valign="top" align="left" style="" nowrap>
		<div style="overflow:hidden; width:100%;">
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;"><tr>
				<td><input id="dsb" type="button" value="Struttura" onclick="dct.swc('dns',this);" style="border-width:1px;width:100%;height:20px;background-color:lightgrey;"></td>
				<td><input id="dxb" type="button" value="Speciali" onclick="dct.swc('dnx',this);" style="border-width:1px;width:100%;height:20px;background-color:#E5E5E5;"></td>
			</tr></table>
		</div>
		</td></tr><tr><td valign="top" style="height:100%;">
		<div style="overflow:auto;position:relative;height:100%;">
		<div id="dns" style="position:absolute;left:0px;top:0px;width:100%;height:100%;"></div>
		<div id="dnx" style="position:absolute;left:0px;top:0px;width:100%;height:100%;visibility:hidden;"></div>
		</div></td></tr></table>
	</div></td>
</tr>
</table>
</body>
</html>