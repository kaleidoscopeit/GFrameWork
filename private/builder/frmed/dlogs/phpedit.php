<?php 
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["frmed/jscript/jsedit.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
 		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
  		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
<body bgcolor="#E5E5E5" style="position:absolute;margin: 0px;width:100%;height:100%;" onload="init()">
<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>
	INSERIRE IL CODICE PHP
	</td></tr><tr><td style="height:100%;">
	<textarea id="ear" wrap="off" onkeyup="updateHistory(event);" onkeydown="return(editManager(event));" style="overflow:auto; left:1%;width:98%;height:98%;white-space: nowrap;""></textarea>
</td></tr><tr><td>
	<table cellpadding="0" cellspacing="0" border="0"><tr><td>
		</td><td width="100%">
		</td><td>
			<input type="button" value="Conferma" onclick="getCode();">
		</td></tr></table>
</td></tr></table>

</body>
</html>