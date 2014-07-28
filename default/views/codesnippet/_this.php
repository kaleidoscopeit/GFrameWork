<?php

//?webget.table.onflush
	
	$this->result_set=array(
		array('name'=>'base:label','description'=>'Esempio di etichetta','view'=>'lablel'),
		array('name'=>'base:image','description'=>'Esempio di immagine','view'=>'lablel'),
		array('name'=>'base:button','description'=>'Esempio di pulsante','view'=>'lablel'),
	);


//?webget.table1.onflush
	
	$this->result_set=array(
		array('name'=>'base:label','description'=>'Esempio di etichetta','view'=>'lablel'),
		array('name'=>'base:image','description'=>'Esempio di immagine','view'=>'lablel'),
		array('name'=>'base:button','description'=>'Esempio di pulsante','view'=>'lablel'),
	);
	
	
//?webget.name.onflush

	$this->caption=$_->webgets['table']->current_record['name'];

//?webget.description.onflush

	$this->caption=$_->webgets["table"]->current_record["description"];	



//?webget.sample_code.code
/*
<pack:stack id="stack">
		<pack:stackelm>
			<pack:vlayout>
				<pack:vlaycell style="background-color:grey;" height="20px">
					<base:label class="labelB" halign="center" valign="middle" caption="Lista di esempi" />
				</pack:vlaycell>
				<pack:vlaycell>
					<data:table id="table" rowheight="48px" data="fake">
						<data:tablecell id="rowcell" style="border-bottom:1px solid grey;" showif="true">
							<pack:hlayout style="border:1ps solid blue;">
								<pack:hlaycell >
									<base:image boxing="32px,32px,left,,4px" src="images/objects.png" />
									<base:label id="name" boxing="50px,20px,5px,top,,4px" halign="left" class="biglabelB"  />
									<base:label id="description" boxing="50px,20px,5px,bottom,,-4px" halign="left" style="color:grey;"  class="labelB" />
								</pack:hlaycell>
								<pack:hlaycell width="50px">
									<form:button id="go_button" boxing="40px,40px,right,,-4px" tip="Visualizza" >
										<base:image boxing="32px,32px,,,,-2px" src="images/go-next.png" />
									</form:button>
								</pack:hlaycell>
							</pack:hlayout>
						</data:tablecell>
					</data:table>
				</pack:vlaycell>
			</pack:vlayout>
		</pack:stackelm>
*/		
//?webget.sample_codae.code
?>
