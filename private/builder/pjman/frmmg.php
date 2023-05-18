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
			startupFunct = {};
			vrs={};
			
			function startup(){
				dirpane=document.getElementById('dirpane');
   			pjnav('fmd');
				fmd.uri='/forms';
				//fmd.ock=function('';
				fmd.odck=function(){
					if(this.itemMime=="xml")parent.wman.ofr(this.itemURL+"/"+this.itemName)
				};
				fmd.lbl='Forms';
				fmd.style.cssText="left:0px;top:0px;width:100%;height:100%;background-color: white;";
				
				dirpane.appendChild(fmd);
				fmd.update();
			};
		</script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body onmousedown="return false;" style="font:14px arial,sans;background-color: #EEEEEE;position:absolute;overflow:hidden;height:100%;width:100%;margin:0px;" onload="startup();" onclick="parent.wman.chkmdl(window);">
			<!-- spazio principale -->
			<table cellpadding="0" cellspacing="0" border="0" style="position:absolute;width:100%;height:100%;"><tbody style="height:100%;"><tr><td style="height:100%;" id="MainPaneA"  valign="top" >
				<!-- spazio selettore rapido -->
				<div style="overflow:auto;width:100%;height:100%;border-right:1px solid grey;">
					<table cellpadding="0" cellspacing="15" border="0" align="left" style="width:192px;height:128px;"><tr><td align="center" width="50%" class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');">
						<img border="0" src="imges/48/form.png"><br><?php echo $lc_msg['frmmg_0'];?>
					</td></tr></table>
					<table cellpadding="0" cellspacing="15" border="0" align="left"  style="width:192px;height:128px;"><tr><td align="center" class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');">
						<img border="0" src="imges/48/locale.png"><br><?php echo $lc_msg['frmmg_1'];?>
					</td></tr></table>
					<table cellpadding="0" cellspacing="15" border="0" align="left"  style="width:192px;height:128px;"><tr><td align="center" class="butt" onclick="alert('<?php echo $lc_msg['gen_0'];?>');">
						<img border="0" src="imges/48/theme.png"><br><?php echo $lc_msg['frmmg_2'];?>
					</td></tr></table>
				</div>
				</td><td style="cursor: e-resize;width:5px;padding-left:2px;"  id="MainPaneL" onmousedown="layman.startPan('H',event,'MainPaneA','MainPaneB');return false;">
				<!-- panner --><img src="imges/handle-v.png" alt="" onmousedown="return(false);">
			</td><td  id="MainPaneB" style="width:80%;height:100%;"  onmousedown="return(false);">
				<!-- spazio albero del progetto -->
				<table cellpadding="0" cellspacing="0" border="0" class="warea" style="width:100%;height:100%;"><tr><td nowrap style="border-bottom:1px solid grey;">
					<img src="imges/tob-new.png" class="minib" title="<?php echo $lc_msg['frmmg_3'];?>" onclick="parent.wman.odl('6&url='+fmd.itemURL+(fmd.itemMime=='inode'?'/'+fmd.itemName:''),'main','width=300,height=150');"><img src="imges/24/refresh.png" title="<?php echo $lc_msg['frmmg_4'];?>" onclick="fmd.update();" class="minib">
				</td></tr><tr><td  valign="top" style="height:100%;">
					<div id="dirpane" style="overflow: hidden; position: relative;width:100%;height: 100%;"></div>
				</td></tr></table>
			</td></tr></tbody></table>
	</body>
