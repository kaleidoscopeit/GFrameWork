<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/layman.js"]=0;
$SG['js'][$tkt]["jscript/pjnav.js"]=0;
$SG['js'][$tkt]["jscript/channel.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<style>
			td{text-align:center;border:1px solid #EEEEEE;font: 13px arial,sans;width:140px;height:100px;}
			td:hover{background-color: lightblue;border: 1px solid grey;cursor:pointer;}
			.sdiv{border-bottom:2px ridge}
			.ldiv{padding-left:15px;padding-top:10px;font:bold 15px arial,sans;}
		</style>
		<script type="text/JavaScript">
			WINS={};
			startupFunct = {};
			OBJS={};VARS={};
			
			function startup(){
			
				/* elementi html utilizzati dal sistema */
				OBJS.dummy=document.getElementById('dummy');				
			};
		</script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body style="position:absolute;overflow:auto;height:100%;width:100%;margin:0px;background-color: #EEEEEE;" onload="startup();">
		<div class="ldiv"><?php echo $lc_msg['config_bldp'];?></div>
		<table cellpadding="0" cellspacing="10" ><tr>
		<td align="center" class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/projects.png"><br><?php echo $lc_msg['config_bldp_0'];?>
		</td><td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/users.png"><br><?php echo $lc_msg['config_bldp_1'];?>
		</td><td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/preferences.png"><br><?php echo $lc_msg['config_bldp_2'];?>
		</td><td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/update.png"><br><?php echo $lc_msg['config_bldp_3'];?>
		</td></tr></table>
		<div class="sdiv"></div>
		<div class="ldiv"><?php echo $lc_msg['config_pjp'];?></div>
		<table cellpadding="0" cellspacing="15"><tr>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/calendar.png"><br><?php echo $lc_msg['config_pjp_0'];?>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/library.png"><br><?php echo $lc_msg['config_pjp_1'];?>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/package.png"><br><?php echo $lc_msg['config_pjp_2'];?>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/users.png"><br><?php echo $lc_msg['config_pjp_3'];?>
		</td></tr></table>
		<div class="sdiv"></div>
		<div class="ldiv"><?php echo $lc_msg['config_dsrc'];?></div>
		<table cellpadding="0" cellspacing="15"><tr>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/database.png"><br><?php echo $lc_msg['config_dsrc_0'];?>
		<td align="center"  class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');" nowrap>
			<img border="0" src="imges/48/query.png"><br><?php echo $lc_msg['config_dsrc_!'];?>Query editor
		</td></tr></table>
	</body>
