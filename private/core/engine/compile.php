<?php

/* compilator
 *
 * This class contains tools in order to compile some parts of the project tree 
 * 
 */

class _engine_compile {
  /* initialization */
  function init()
  {
    echo "a";
  }
  
  function build($target)
  {
    /* loopback to the buck */
    $_ = $this;

    switch($target) {
      
      case 'webgets' :
        /* find all webegets's js code */
        
        $this->WEBGETS_PATH;
        break;
    }

  }
}