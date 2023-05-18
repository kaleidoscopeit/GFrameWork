<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/channel.js"]=0;
$SG['js'][$tkt]["frmed/jscript/prpty.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>
			.a{border-width:1px;width:100%;height:20px;background-color:#E5E5E5;}
			.c{position:absolute;left:0px;top:0px;width:100%;visibility:hidden;}
		</style>
		<title>MWE - Editor delle maschere</title>
  		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
   	<script type="text/JavaScript" src="js.php"></script>
	</head>
	<body style="position:absolute;overflow:hidden;height: 100%;width:100%;margin: 0px;background-color:#EBEBEB;" onload="pty.str();eval(wman.cbf);" >

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
	</body>
</html>