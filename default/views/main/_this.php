<?php

//?webget.table.onflush
	
$this->result_set=array(
   array('description'=>'GENERAL',
         'type'=>'caption'),
         
	 array('description'=>'Framework Structure',
         'type'=>'item',
  	     'view'=>'framework_structure'),
  	     
	 array('description'=>'Installation',
         'type'=>'item',
	       'view'=>'installation'),
	       
	 array('description'=>'Boxing',
         'type'=>'item',
	       'view'=>'boxing'),
	       
   array('description'=>'WEBGETS LIBRARY',
         'type'=>'caption'),

   array('description'=>'schedule:datepicker',
         'type'=>'item',
         'view'=>'webgets/schedule/datepicker'),         


   array('description'=>'Codesnippet',
         'type'=>'item',
         'view'=>'codesnippet'),         
);

//?webget.tablecell.onflush

  $this->onclick=""	
?>