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
	 			window.resizeTo(450, 300);
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
				chn.get.req='tables';
				chn.onload=function(d){this.parent.parse(d)};
				chn.send();
	 		}

			function tdtl(d){
				if(!d){
					chn.url='./';
					chn.get['100']=null;
					chn.get.dbc=wpr.cwb.param['DSN'];
					chn.get.req='tinfo';
					chn.get.tbl=dge('sel').value;
					chn.onload=function(d){this.parent.tdtl(d)};
					chn.send();
					return;
				}
				
				d=eval('('+d+')');
				dge('tdtl').innerHTML='';
					table=dcr('table');dge('tdtl').appendChild(table);
					table.width='90%';
					table.cellPadding='5';
					table.cellSpacing='5';
				while(d[0]){
					tr=dcr('tr');table.appendChild(tr);
					td=dcr('td');tr.appendChild(td);
					td.noWrap=1;
					td.width="1";
					td.innerHTML=d[0][0]+' :';
					td=dcr('td');tr.appendChild(td);
					td.noWrap=1;
					td.innerHTML=d[0][1];
					d.shift();
				}
			}   		
   		function parse(d){
				try{d=eval(d)}
				catch(e){alert(d);return;}

				for(var i in d){
					o=dcr('option');
					o.text=d[i];
					o.value=d[i];
					dge('sel').appendChild(o);
				}
   			dge('sel').value=pv;
   		}
    		
   		function ups(){
   			/* Se la tabella cambia, resetta i valori dei campi collegati */
   			if(pv!=dge('sel').value){
   				wop.wnd[oid].wbt.upy('FIELDS','',cwb);
					wop.wnd[oid].wbt.upy('KEYFIELD','',cwb);
   			}
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
		<!-- spazio menu -->SELEZIONA UNA TABELLA
			</td></tr><tr><td  onmousedown="return false;" style="height:100%;">
			<!-- spazio principale -->
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;"><tr><td style="width:50%;height:100%;" id="MainPaneA">
				<!-- spazio lista sorgenti disponibili -->
				<select onclick="tdtl();" id="sel" size="2" style="width:100%;height:100%;border:1px solid grey;"></select>
			</td><td style="background-color: #EEEEEE; cursor: e-resize; "  id="MainPaneL" onmousedown="layman.startPan('H',event,'MainPaneA','MainPaneB');return false;">
				<!-- panner --><img src="imges/handle-v.png" border="0" alt="" onmousedown="return false;">
			</td><td id="MainPaneB" align="center" style="width:50%;height:1px;background-color:#EEEEEE;border:1px solid grey;"  onmousedown="return(false);">
				<!-- spazio dettagli tabella -->
 				<div id="tdtl" style="overflow:auto;height:100%;"></div>
			</td></tr></table>
			</td></tr><tr style="background-color: #EEEEEE; height: 40px; "><td align="right" style="border-top: 1px solid lightgrey;">
			<!-- spazio pulsanti -->
				<button type="button" onclick="self.close();">Annulla</button>&nbsp;&nbsp;
				<button type="button" onclick="ups();">Conferma</button>&nbsp;&nbsp;
		</td></tr></table>
	</body>