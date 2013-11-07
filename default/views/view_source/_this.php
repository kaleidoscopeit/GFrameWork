<?php

//?webget.xml_code.onflush

	if(strpos($_GET['source'], '..') == -1) die;
  $this->code = file_get_contents('views/' . $_GET['source'] . '/_this.xml');
  

?>
