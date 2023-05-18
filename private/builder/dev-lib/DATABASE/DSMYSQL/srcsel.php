<?php
/* MySQL connection select dialog */
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/layman.js"]=0;
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MWE - Selezione database</title>
		<style>
			button {background-color: lightgrey;border-width:1px;height:20px;}
			input {border-width:1px;}
		</style>	
 		<script type="text/JavaScript">
<?php
// legge dalla directory i nomi dei files che definiscono le connessioni e
// crea una array contenete i nomi delle connessioni
$src='../../'.$SG['bld']['root'].'/dbc/';
echo "dbs={};\n";
$handle=opendir($src);
$target= Array();
// Per ogni directory di collezione di librerie trovata...
while (false!==($dsn=readdir($handle))) {
	if($dsn != '.' && $dsn != '..' && is_file($src.$dsn)){
		// include ricorsivamente le definizioni dei database e genera il codice javascript per il comportamento dinamico
		include($src.$dsn);
		/* if database type is mysql append output*/
		if($db_type=="mysql"){
			echo "dbs['".rtrim($dsn,'.php')."']=new Array('".$db_name."','".$db_type."','".$db_host."');\n";
			$target[]=rtrim($dsn,'.php');
		}
	}
}
closedir($handle);
?>
	 		function startup(){
	 			window.resizeTo(400, 300);
	 			wop=rfw.wman;
				wpr=wop.gwnd('topan').pty;
				oid=wpr.wid;
				pn=wpr.cfld.prpid;
				pv=wpr.cfld.value;	 			
				cwb=wpr.cwb;
				if(!wop.wnd[oid].document){
					alert('Il Form che contiene il Webget è attualmente chiuso.');
					self.close();
				}
				if(!cwb){
					alert('Il Webget a cui si fa riferimento non è accessibile! \nProbabilmente è stato eliminato.');
					self.close();
				}
				if(pv){
	 				dge('sel').value=pv;
	 				dge('sel').onclick();
	 			}
	 		}
   		
   		function update(dsn){
   			dge('dbd1').innerHTML=dbs[dsn][0];
   			dge('dbd2').innerHTML=dbs[dsn][1];
   			dge('dbd3').innerHTML=dbs[dsn][2];
    		}
    		
    		function updateSource(){
    			window.opener.VARS.currField.value=document.getElementById('sel').value;
    			window.opener.VARS.currField.onchange();
    			self.close();
    		}
    		function ups(){
				wop.wnd[oid].wbt.upy(pn,dge('sel').value,cwb);
				wop.wnd[oid].wbt.upb();
				self.close();
			}
 		</script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
		<script type="text/JavaScript" src="js.php?<?php echo $tkt?>"></script>
	</head>
	<body style="font:12px arial,sans;position:absolute;overflow:hidden;height:100%;width:100%;margin:0px;background-color:lightgrey;" onload="startup();">
		 <input id="dummy" type="text" style="position:absolute;left:-10px;width:1px;height:1px;">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr style="background-color: #E5E5E5; height: 30px; ">
			<td nowrap style="text-align:center;font:20px arial,sans;border-bottom: 1px solid lightgrey;background-color:#EEEEEE;"  onmousedown="return false;">
		<!-- spazio menu -->SELEZIONA UN DATABASE
			</td></tr><tr><td  onmousedown="return false;" style="height:100%;">
			<!-- spazio principale -->
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td style="width:40%;height:100%;" id="MainPaneA">
				<!-- spazio lista sorgenti disponibili -->
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>
					</td></tr><tr><td  valign="top" style="height:100%;background-color: white;border:1px solid grey;">
						<div id="dp" style="overflow:auto;position:relative;height:100%;">
							<select  id="sel" size="2" style="width:100%;height:100%;border-width:0px;" onclick="update(this.value);">
								<?php
									foreach($target as $value){echo "<option label=\"".$value."\" >".$value."</option>\n";}
									// include ricorsivamente le definizioni dei database
									?>
							</select>
						</div>
					</td></tr></table>
				</td><td style="background-color: #EEEEEE; cursor: e-resize; "  id="MainPaneL" onmousedown="layman.startPan('H',event,'MainPaneA','MainPaneB');return false;">
				<!-- panner --><img src="imges/handle-v.png" border="0" alt="" onmousedown="return false;">
			</td><td  id="MainPaneB" align="center" style="width:100%;height:100%;background-color: #EEEEEE;border:1px solid grey;"  onmousedown="return(false);">
				<!-- spazio dettagli database -->
				<table cellpadding="5" cellspacing="5" border="0" width="100%">
					<tr><td width="1%" nowrap><?php echo $lc_msg['srcsel_1'];?></td>
					<td id="dbd1"></td>
					</tr><tr><td nowrap><?php echo $lc_msg['srcsel_2'];?></td>
					<td  id="dbd2" nowrap></td></tr>
					<tr><td nowrap><?php echo $lc_msg['srcsel_3'];?></td>
					<td  id="dbd3"></td></tr></table>
					</td></tr></table>
			</td></tr><tr style="background-color: #EEEEEE; height: 40px; "><td align="right" style="border-top: 1px solid lightgrey;">
			<!-- spazio pulsanti -->
				<button type="button" onclick="self.close();">Annulla</button>&nbsp;&nbsp;
				<button type="button" onclick="ups();">Conferma</button>&nbsp;&nbsp;
		</td></tr></table>
	</body>
