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
  
  function build()
  {
    $ftimes         = array();
    $js_static      = $this->static[$this->CALL_URI]['js'];

    $js_raw         = $js_static['raw'];
    $expires         = 60*3;

  //  unset($this->static[$this->CALL_URI]['js']);

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