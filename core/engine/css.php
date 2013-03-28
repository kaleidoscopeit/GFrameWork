<?php

/* css builder
 *
* This class contains all tool in order to address and build
* css's realted to a view.
*
*/

class _engine_css {

  function init()
  {
    /* initialization */

    $this->webget_path           = '../core/webgets/';
  }
  
  function build($source_url)
  {
    header('Content-type: text/css');
    
    $this->CALL_UUID = array_keys($_GET)[1];
    $this->STYLE_REGISTRY_PREFIX = $this->static['style_registry_prefix'];

    foreach((array) @$this->static[$this->CALL_UUID]['style_registry'] as $key => $value){
      echo '.'.$this->STYLE_REGISTRY_PREFIX.$key.'{'.$value.'}';
    }

    unset($this->static[$this->CALL_UUID]['style_registry']);
  }
}

?>