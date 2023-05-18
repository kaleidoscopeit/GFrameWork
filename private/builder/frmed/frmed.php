<?php 
if(!$SG)die;
$SG['js']['frmed']["jscript/sysapi.js"]=0;
$SG['js']['frmed']["frmed/jscript/startup.js"]=0;
$SG['js']['frmed']["jscript/channel.js"]=0;
$SG['js']['frmed']["frmed/jscript/editor.js"]=0;
$SG['js']['frmed']["frmed/jscript/wbgman.js"]=0;
$SG['js']['frmed']["frmed/jscript/wbgmov.js"]=0;
$SG['js']['frmed']["frmed/jscript/wbgrsz.js"]=0;
$SG['js']['frmed']["frmed/jscript/wbgstk.js"]=0;
$SG['js']['frmed']["frmed/jscript/wbgvis.js"]=0;
$SG['js']['frmed']["frmed/jscript/fltman.js"]=0;
$SG['js']['frmed']["frmed/jscript/libchrg.js.php"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<style>
			.flb{width:5px;height:5px;background-color:white;border:1px solid black;}
			.tkb {background-color:lightgrey;border-width:1px;}
			.rbt{
				text-align:center;
				width:65px;
				margin:1px;
				border-width:0px;
				font:12px arial,sans;
			}
			.rbt:hover{
				background-color:lightblue;
				border:1px solid grey;
				margin:0px;
				width:63px;
				cursor:pointer;
			}
			.rbs{
				background-color:#a1c8d5;
				border:1px solid grey;
				margin:0px;
				width:63px;
				cursor:pointer;
				text-align:center;
				font:12px arial,sans;
			}
		</style>
		<title><?php echo $lc_msg['frmed'];?></title>
		<link rel="stylesheet" type="text/css" href="../../<?php echo $_SESSION['bld']['root']?>/css.php">
 		<script type="text/JavaScript" src="js.php?frmed"></script>
  		<script type="text/JavaScript" src="js.php?frmed"></script>
	</head>
	<body  style="position:absolute;overflow:hidden;height: 100%;width:100%;margin: 0px;background-color:#EBEBEB;" onload="stu.str();
<?php $_GET['f'] ? print("edt.lpg('forms/".$_GET['f']."');"):null;?>
">
		<input id="dummy" type="text" style="position:absolute;top:10px;width:0px;height:0px;">
		<table cellpadding="0" cellspacing="0" border="0" style="position:absolute;width:100%;height:100%;"><tbody><tr>
			<td style="background-color:lightgrey;" colspan="2">
			<!-- switch button bar -->
				<table cellpadding="0" cellspacing="0" border="0"><tr>
				<td><input type="button" class="tkb" value="<?php echo $lc_msg['frmed_mod'];?>" style="background-color: #EBEBEB;" onclick="edt.rsw(this,'modu_task');"></td>
				<td><input type="button" class="tkb" value="<?php echo $lc_msg['frmed_tbx'];?>" onclick="edt.rsw(this,'visu_task');"></td>
				<td><input type="button" class="tkb" value="<?php echo $lc_msg['frmed_wbt'];?>" onclick="edt.rsw(this,'disp_task');"></td>
				<td><input type="button" class="tkb" value="<?php echo $lc_msg['frmed_sys'];?>" onclick="edt.rsw(this,'sist_task');"></td>
				</tr></table>

		</td></tr><tr style="height: 55px;"><td id="tka" style="border-bottom:1px solid lightgrey;width:100%;">
			<!-- icon toolbar (per ogni scheda definire la larghezza fisica pari alla somma delle icone contenute)-->

			<!-- modulo -->			
			<table id="modu_task" cellpadding="0" cellspacing="0" border="0" style="width:130px;"><tr height="50">
				<td class="rbt" onclick="edt.spg();" title="<?php echo $lc_msg['frmed_mod_0c'];?>">
					<img src="imges/tob-save.png""><br><?php echo $lc_msg['frmed_mod_0'];?>
				</td><td class="rbt"
						onclick="rfw.wman.own('pre',0,'../../<?php echo $_SESSION['bld']['root']?>/?'+edt.struct.url.slice(0,edt.struct.url.length-4),'width=600,height=400',0,'forms');"
						title="<?php echo $lc_msg['frmed_mod_1c'];?>" >
					<img src="imges/tob-prev.png"><br><?php echo $lc_msg['frmed_mod_1'];?>
				</td>
			</tr></table>

			<!-- visualizza -->			
			<table id="visu_task" cellpadding="0" cellspacing="0" border="0" style="display:none;width:195px" ><tr height="50">
				<td class="rbt"
					onclick="rfw.wman.own('topan',0,'?17','width=300,height=650,left=20,top=20',0,'forms',0,1);" title="<?php echo $lc_msg['frmed_tbx_0c'];?>">
					<img src="imges/tob-strct.png"><br><?php echo $lc_msg['frmed_tbx_0'];?>
				</td>
			</tr></table>

			<!-- disposizione -->
			<table id="sist_task" cellpadding="0" cellspacing="0" border="0" style="display:none;width:65px;" ><tr height="50">
				<td class="rbt"
 					onclick="rfw.wman.own('ene',0,'?16','width=350,height=250,scrollbars=yes',0,'forms',0,1,'');" title="<?php echo $lc_msg['frmed_sys_0c'];?>">
				<img src="imges/tob-prpty.png"><br><?php echo $lc_msg['frmed_sys_0'];?>
				</td>
			</tr></table>

			<!-- sistema -->
			<table id="disp_task" cellpadding="0" cellspacing="2" border="0" style="display:none;width:100%;" ><tr height="50">
				<td class="rbt" 
 					onclick="wbt.rmv();" title="<?php echo $lc_msg['frmed_wbt_0c'];?>">
				<img src="imges/edit-delete.png"><br><?php echo $lc_msg['frmed_wbt_0'];?>
				</td>				
				<td style="border-width:1px;border-style:inset;"></td>
				<td class="rbt" 
 					onclick="wbt.mvu();" title="<?php echo $lc_msg['frmed_wbt_1c'];?>">
				<img src="imges/tob-bring-forward.png"><br><?php echo $lc_msg['frmed_wbt_1'];?>
				</td>
				<td class="rbt"
 					onclick="wbt.mvd();" title="<?php echo $lc_msg['frmed_wbt_2c'];?>" >
				<img src="imges/tob-bring-backward.png"><br><?php echo $lc_msg['frmed_wbt_2'];?>
				</td>
				<td class="rbt"
 				onclick="wbt.mvt();" title="<?php echo $lc_msg['frmed_wbt_3c'];?>">
				<img src="imges/tob-to-top.png"><br><?php echo $lc_msg['frmed_wbt_3'];?>
				</td>
				<td class="rbt"
 					onclick="wbt.mvb();" title="<?php echo $lc_msg['frmed_wbt_4c'];?>">
				<img src="imges/tob-to-bottom.png"><br><?php echo $lc_msg['frmed_wbt_4'];?>
				</td>
				<td style="border-width:1px;border-style:inset;"></td>
				<td class="rbt"
 					onclick="wbt.hid();" title="<?php echo $lc_msg['frmed_wbt_5c'];?>">
				<img src="imges/tob-showhide.png"><br><?php echo $lc_msg['frmed_wbt_5'];?>
				</td>
				<td class="rbt"
 					onclick="wbt.sha();" title="<?php echo $lc_msg['frmed_wbt_6c'];?>">
				<img src="imges/tob-showhide.png"><br><?php echo $lc_msg['frmed_wbt_6'];?>
				</td>
			</tr></table>
			
		</td><td id="taskarea" style="border-bottom:1px solid lightgrey;border-left:2px groove lightgrey;">
			<!-- alert Area -->
			<div id="aar" style="width:65px;height:100%;text-align:center;"></div>
		</td></tr><tr><td valign="top" align="center" style="width:100%;height:100%;" colspan="2">
			<!-- spazio designer -->
<div style="overflow:hidden;position:relative;background-color:white;width:100%;height:100%;">
  	<div id="designspace" style="position:absolute;overflow:auto;height:100%;width:100%;">	</div>
	<div id="flt"  style="position:absolute;left:0px;top:0px;width:100%;height:100%;visibility:hidden;" >
		<table cellpadding="0" cellspacing="0" border="0" style="border:1px dotted lightgrey;width:100%;height:100%;">
			<tr>
				<td><div class="flb" style="cursor:nw-resize;margin-left:-4px;margin-top:-4px;" onmousedown="wbt.rst('NE',event);">&nbsp;</div></td>
				<td width="100%"></td>
				<td><div class="flb" style="cursor:ne-resize;margin-right:-4px;margin-top:-4px;" onmousedown="wbt.rst('NW',event);">&nbsp;</div></td>
			</tr><tr>
				<td ></td>
				<td style="width:100%;height:100%"></td>
				<td></td>
			</tr><tr>
				<td><div class="flb" style="cursor:sw-resize;margin-left:-4px;margin-bottom:-4px;" onmousedown="wbt.rst('SE',event);">&nbsp;</div></td>
				<td width="100%"></td>
				<td><div class="flb" style="cursor:se-resize;margin-right:-4px;margin-bottom:-4px;" onmousedown="wbt.rst('SW',event);">&nbsp;</div></td>
			</tr>
		</table>
	</div>	
</div>

		</td></tr></tbody></table>
	</body>
</html>