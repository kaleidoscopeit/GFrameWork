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

?>
