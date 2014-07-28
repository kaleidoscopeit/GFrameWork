<?php

/* js builder
 *
* This class contains all tool in order to address and build
* the java scripts related to a view.
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
    header('Content-type: text/javascript');
    echo implode('', $this->static[$this->CALL_URI]['js']['raw']);
  }
}
?>