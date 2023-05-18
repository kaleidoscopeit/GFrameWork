<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/pjnav.js"]=0;
$SG['js'][$tkt]["jscript/layman.js"]=0;
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/channel.js"]=0;
$SG['js'][$tkt]["dev-lib/COMMON/IMAGE/imgsel.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MWE - Selezione immagine</title>
		<style>
			button {background-color: lightgrey;border-width:1px;height:20px;}
			input {border-width:1px;}
		</style>	
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body style="position:absolute;overflow:hidden;height:100%;width:100%;margin:0px;background-color:lightgrey;" onload="str('<?php echo $SG['bld']['root']?>');">
		 <input id="dummy" type="text" style="position:absolute;left:-10px;width:1px;height:1px;">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr style="background-color: #E5E5E5; height: 30px; ">
			<td nowrap style="text-align:center;font:20px arial,sans;border-bottom: 1px solid lightgrey;background-color:#EEEEEE;"  onmousedown="return false;">
		<!-- spazio menu -->SELEZIONA UN IMMAGINE
			</td></tr><tr style="height: 30px; ">
				<td  nowrap style="text-align:left;font: bold 12px arial,sans;border-bottom: 1px solid lightgrey;background-color:#EEEEEE;">
			<!-- spazio indirizzo -->Icona:&nbsp;&nbsp;&nbsp;<input id="pst" type="text" onchange="image.src=this.value" style="border-width:1px;width:81%;">
			</td></tr><tr><td  onmousedown="return false;" style="height:100%;">
			<!-- spazio principale -->
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td style="width:40%;height:100%;" id="MainPaneA">
				<!-- spazio albero del progetto -->
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>
					</td></tr><tr><td  valign="top" style="height:100%;background-color: white;border:1px solid grey;">
						<div id="dp" style="overflow:auto;position:relative;height:100%;"></div>
					</td></tr></table>
				</td><td style="background-color: #EEEEEE; cursor: e-resize; "  id="MainPaneL" onmousedown="layman.startPan('H',event,'MainPaneA','MainPaneB');return false;">
				<!-- panner --><img src="imges/handle-v.png" border="0" alt="" onmousedown="return false;">
			</td><td  id="MainPaneB" align="center" style="width:100%;height:100%;background-color: #EEEEEE;border:1px solid grey;"  onmousedown="return(false);">
				<!-- spazio anteprima immagine --><img id="image" style="height:100px;">
			</td></tr></table>
			</td></tr><tr style="background-color: #EEEEEE; height: 40px; "><td align="right" style="border-top: 1px solid lightgrey;">
			<!-- spazio pulsanti -->
				<button type="button" onclick="pst.value=prv;ups();self.close();">Annulla</button>&nbsp;&nbsp;
				<button type="button" onclick="ups();">Conferma</button>&nbsp;&nbsp;
		</td></tr></table>
	</body>