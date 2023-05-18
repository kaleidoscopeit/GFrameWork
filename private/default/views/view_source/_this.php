<?php die;

//?webget.xml_code.onflush

	if(strpos($_GET['source'], '..') == -1) die;
  $this->code = file_get_contents($_->APP_PATH . '/views/' . $_GET['source'] . '/_this.xml'); 
  //echo $this->code;

?>
