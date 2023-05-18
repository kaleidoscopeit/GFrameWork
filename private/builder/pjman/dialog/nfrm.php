<?php
if(!$SG)die;
$SG['js']["jscript/sysapi.js"]=0;
$SG['js']["jscript/channel.js"]=0;
if(!$_GET['nme']){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<style>
			.butt{text-align:center;border:1px solid #EEEEEE;font: 16px arial,sans;}
			.butt:hover{background-color: lightblue;border: 1px solid grey;cursor:pointer;}
			.buttons{border-width:1px;}
			.minib{padding:3px;}
			.minib:hover{cursor:pointer;background-color: lightblue;padding:3px;}
			.warea{border-left:1px solid grey;background-color: #EEEEEE;}
		</style>
		<script type="text/JavaScript">
			function submit(){
				chn=new channel();
				
				alert(chn);
				
			}
			
			function chktxt(e){
				if(e.keyCode=='191' || e.keyCode=='111')return false;
			}
		</script>
		<script type="text/JavaScript" src="js.php"></script>
		<script type="text/JavaScript" src="js.php"></script>
	</head>
	<body style="font:14px arial,sans;background-color: #EEEEEE;position:absolute;overflow:hidden;height:100%;width:100%;margin:0px;">
	<div style="height:100%;">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr>
			<td colspan="2" height="30" style="text-align:center;font:20px arial,sans;border-bottom:1px solid lightgrey;background-color:#EEEEEE;">
				<?php echo $lc_msg['frmmg_nf'];?>
			</td>
			</tr><tr>
				<td>&nbsp;&nbsp;&nbsp;Percorso :</td>
				<td align="right"><input type="text" disabled name="url" value="<?php echo ($_GET['url']?$_GET['url']:'');?>">&nbsp;</td>
			</tr><tr>
				<td>&nbsp;&nbsp;&nbsp;Nome :</td>
				<td align="right"><input type="text" onkeydown="return(chktxt(event));">&nbsp;</td>				
			</tr><tr>
				<td height="35" colspan="2" align="right" valign="middle" style="border-top:1px solid lightgrey;">
					<input type="button" value="<?php echo $lc_msg['frmmg_nf_3'];?>" class="buttons" onclick="self.close();">
					<input type="button" value="<?php echo $lc_msg['frmmg_nf_2'];?>" class="buttons" onclick="submit();">
					&nbsp;
				</td>
			</tr></table>
	</div>
	<div style="height:100%;position:absolute;left:0px;top:0px;visibility:hidden;">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr>
			<td id="title" height="30" style="text-align:center;font:20px arial,sans;border-bottom:1px solid lightgrey;background-color:#EEEEEE;">
			</td>
			</tr><tr>
				<td><img id="image" border="0" alt=""></td>
			</tr><tr>
				<td height="35" align="right" valign="middle" style="border-top:1px solid lightgrey;">
					<input type="button" value="<?php echo $lc_msg['frmmg_nf_4'];?>" class="buttons" onclick="self.close();">
					&nbsp;
				</td>
			</tr></table>
	</div>
	</body>
</html>
<?php } else { ?>
<?php } ?>