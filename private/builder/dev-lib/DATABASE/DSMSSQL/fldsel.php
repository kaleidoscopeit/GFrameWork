<?php
if(!$SG)die;
$tkt=substr(microtime(),2,4);
$SG['js'][$tkt]["jscript/layman.js"]=0;
$SG['js'][$tkt]["jscript/sysapi.js"]=0;
$SG['js'][$tkt]["jscript/channel.js"]=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MWE - Selezione tabella</title>
		<style>
			button {background-color: lightgrey;border-width:1px;height:20px;}
			input {border-width:1px;}
		</style>	
 		<script type="text/JavaScript">
	 		function startup(){
	 		shd.style.visibility="visible";
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
	 			
				/* crea un canale dati per questa funzione */
				this.chn=new channel();
				chn.parent=this;
				chn.url='./';
				chn.get['100']=null;
				chn.get.dbc=wpr.cwb.param['DSN'];
				chn.get.tbl=wpr.cwb.param['TABLE'];
				chn.get.req='fields';
				chn.onload=function(d){this.parent.parse(d)};
				chn.send();
	 		}
   		/* riempie la select con i valori */
   		function parse(d){
				try{d=eval(d)}
				catch(e){alert(d);return;}

				for(var i in d){
					o=dcr('option');o.text=d[i];o.value=d[i];
					dge('sel').appendChild(o);
				}
				
				if(pv=='*'){dge('sel').disabled=dge('chk').checked=true}
				else {
					s=dge('sel').options;
					for(i=0;i<s.length;i++){
						if(pv.indexOf(','+s[i].value)>-1|pv.indexOf(s[i].value+',')>-1|pv==s[i].value){
							s[i].selected=true
						}
					}
				}

   		}

			/* Seleziona tutti i campi*/
			function all(d){
				if(d==true){dge('sel').disabled=true;}
				else {dge('sel').disabled=false;}
			}
    		function updateSource(){
    			window.opener.VARS.currField.value=document.getElementById('sel').value;
    			window.opener.VARS.currField.onchange();
    			self.close();
    		}
    		function ups(){
    			if(dge('sel').disabled==true){
    				o='*'
    			} else {
    				o=new Array();s=dge('sel').options;
					for(i=0;i<s.length;i++){if(s[i].selected)o.push(s[i].value)}
				}
				wop.wnd[oid].wbt.upy(pn,o,cwb);
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
		<!-- spazio menu -->SELEZIONA UNO O PIÙ CAMPI
			</td></tr><tr><td  onmousedown="return false;" style="height:100%;">
			<!-- spazio principale -->
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td style="width:100%;height:100%;" id="MainPaneA">
				<!-- spazio lista sorgenti disponibili -->
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td>
					</td></tr><tr><td  valign="top" style="height:100%;background-color: white;border:1px solid grey;">
						<div id="dp" style="overflow:auto;position:relative;height:100%;">
							<select  id="sel" multiple size="2" style="width:100%;height:100%;border-width:0px;"></select>
						</div>
					</td></tr></table>
				</td><td style="background-color: #EEEEEE; cursor: e-resize; "  id="MainPaneL" onmousedown="layman.startPan('H',event,'MainPaneA','MainPaneB');return false;">
				<!-- panner --><img src="imges/handle-v.png" border="0" alt="" onmousedown="return false;">
			</td><td  id="MainPaneB" align="center" style="width:100%;height:100%;background-color: #EEEEEE;border:1px solid grey;"  onmousedown="return(false);">
			</td></tr></table>
			</td></tr><tr style="background-color: #EEEEEE; height: 40px; "><td align="right" style="border-top: 1px solid lightgrey;">
			<!-- spazio pulsanti -->
				<input type="checkbox" id="chk" onclick="all(this.checked);">&nbsp;&nbsp;
				<input type="button" onclick="self.close();" value="Annulla">&nbsp;&nbsp;
				<input type="button" onclick="ups();" value="Conferma">&nbsp;&nbsp;
		</td></tr></table>
	</body>