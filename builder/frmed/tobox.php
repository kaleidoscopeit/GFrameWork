<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["frmed/jscript/tbxchrg.js.php"]=0;
$SG['js'][$tkt]["frmed/jscript/tbxman.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>
			.widbutt{font: 12px arial,sans;}
			.widbutt:hover{background-color: lightblue;cursor:pointer;}
		</style>
		<title>MWE - Editor delle maschere</title>
 		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
<!--   		<script type="text/JavaScript" src="js.php"></script> -->
	</head>
	<body style="position:absolute;overflow:hidden;height: 100%;width:100%;margin: 0px;background-color:#EBEBEB;" onload="start();">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;border:1px solid grey;background-color:#E5E5E5;"><tr><td valign="top" style="height:20px;">
		<select size="0" id="selector" onchange="tbx.switch(this.value);" style="width:100%;border:1px solid grey;"></select>
		</td></tr><tr><td valign="top" style="height:100%;">
		<div id="tobox" style="overflow:hidden;position:relative;height:100%;"></div>
		</td></tr></table>
	</body>
</html>
