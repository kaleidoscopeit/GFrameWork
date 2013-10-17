<?php

/* js builder
 *
* This class contains all tool in order to address and build
* the java scrips realted to a view.
*
*/

class _engine_js {

  function init()
  {
    /* initialization */
    if(!$this->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }
  
  function build($source_url)
  {
    $ftimes         = array();
    $source_url     ='views/' . $source_url . '/_this.xml';
    $CALL_UUID       = array_pop(array_keys($_GET));
    $js_static      = $this->static[$CALL_UUID]['js'];
    $js_raw         = $js_static['raw'];
    $expires         = 60*3;

    unset($this->static[$CALL_UUID]['js']);
    
    /* gets the file list and modify tyme */
    switch($this->CALL_TARGET){
      case 'view' :
        break;

      default :
        echo 'WRONG_TARGET';
    }

    header('Content-type: text/javascript');

    switch($this->CALL_TARGET){
      case 'view' :
        echo implode('', $js_raw);
        break;
    }


  }
 
}

?>