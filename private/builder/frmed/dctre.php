<?php 
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["frmed/jscript/dctree.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>

		</style>
		<title>MWE - Editor delle maschere</title>
 		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
  		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body style="position:absolute;overflow:hidden;height: 100%;width:100%;margin:0px;background-color:#EBEBEB;" onload="dct.st();">

	<table cellpadding="1" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;"><tr><td valign="top" align="left" style="" nowrap>
		<div style="overflow:hidden; width:100%;">
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
<tr>
<td><input id="dsb" type="button" value="Struttura" onclick="dct.swc('dns',this);" style="border-width:1px;width:100%;height:20px;background-color:lightgrey;"></td>
<td><input id="dxb" type="button" value="Speciali" onclick="dct.swc('dnx',this);" style="border-width:1px;width:100%;height:20px;background-color:#E5E5E5;"></td>
</tr>
</table>
		</div>
	</td></tr><tr><td valign="top" style="height:100%;">
	<div style="overflow:auto;position:relative;height:100%;">
	<div id="dns" style="position:absolute;left:0px;top:0px;width:100%;height:100%;"></div>
	<div id="dnx" style="position:absolute;left:0px;top:0px;width:100%;height:100%;visibility:hidden;"></div>
	</div></td></tr></table>

	</body>
</html>