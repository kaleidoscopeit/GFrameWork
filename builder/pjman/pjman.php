<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/winman.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title><?php echo $lc_msg['pjman'];?></title>
		<style>
			.butt{
				text-align:center;
				border:1px solid #EEEEEE;
				font: 16px arial,sans;
			}
			.butt:hover{
				background-color: lightblue;
				border: 1px solid grey;
				cursor:pointer;
			}
			.buttons{
				border-width:1px;
			}
		</style>
		<script type="text/JavaScript">
			vrs={};
			
			function startup(){
				/* Imposta le proprietà per la gestione delle finestre alla finestra main ovvero quella in cui è inclusa
					questa funzione */
				wman.regw(window,'main','main',null,null);
				
				/* registra la sottofinestra finestra presso il gestore finestre */
				dge('icha').onload=function(){with(this){
					wman.regw(this.contentWindow,'manage','main','main',null);
					if(/login.php/.test(this.contentWindow.document.location))parent.document.location='login.php';
				}};
				dge('ichb').onload=function(){with(this){
					wman.regw(this.contentWindow,'frmmg','main','main',null);
					if(/login.php/.test(this.contentWindow.document.location))parent.document.location='login.php';
				}};
				dge('ichc').onload=function(){with(this){
					wman.regw(this.contentWindow,'rptmg','main','main',null);
					if(/login.php/.test(this.contentWindow.document.location))parent.document.location='login.php';
				}};
			}
			
			function hlb(me,it){
				dge('bta').style.backgroundColor="";
				dge('btb').style.backgroundColor="";
				dge('btc').style.backgroundColor="";
				dge('btd').style.backgroundColor="";
				dge('bte').style.backgroundColor="";
				dge(me).style.backgroundColor="lightgrey";
				
				dge('icha').style.display="none";
				dge('ichb').style.display="none";
				dge('ichc').style.display="none";
				dge(it).style.display="block";				
			}
			

		</script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body style="font:14px arial,sans;position:absolute;top:0px;overflow:hidden;height:100%;width:100%;margin:0px;background-color:lightgrey;" onload="startup();">

		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr style="background-color:#E5E5E5;height:30px; ">
			<td style="text-align:center;font:20px arial,sans;border-bottom:1px solid lightgrey;background-color:#EEEEEE;"  onmousedown="return(false);">
		<!-- spazio menu --><?php echo $lc_msg['pjman_0'];?>
			</td></tr><tr><td  style="height:20px;">
				<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>
					<button id="bta" style="border-width:1px;width:100%;background-color:lightgrey;" onclick="hlb(this.id,'icha');"><?php echo $lc_msg['pjman_1'];?></button>
				</td><td>
					<button id="btb" style="border-width:1px;width:100%;" onclick="hlb(this.id,'ichb');"><?php echo $lc_msg['pjman_2'];?></button>
				</td><td>
					<button id="btc" style="border-width:1px;width:100%;" onclick="hlb(this.id,'ichc');"><?php echo $lc_msg['pjman_3'];?></button>
				</td><td>
					<button id="btd" style="border-width:1px;width:100%;" onclick="alert('<?php echo $lc_msg['gen_0'];?>');"><?php echo $lc_msg['pjman_4'];?></button>
				</td><td>
					<button id="bte" style="border-width:1px;width:100%;" onclick="alert('<?php echo $lc_msg['gen_0'];?>');"><?php echo $lc_msg['pjman_5'];?></button>
				</td></tr></table>
			</td></tr><tr><td  onmousedown="return(false);" style="height:100%;">
				<iframe id="icha" style="width:100%;height:100%;border-width:0px;" src="?1" ></iframe>
				<iframe id="ichb" style="width:100%;height:100%;border-width:0px;display:none;" src="?2" ></iframe>			
				<iframe id="ichc" style="width:100%;height:100%;border-width:0px;display:none;" src="?3" ></iframe>
		</td></tr></table>
	</body>
</html>