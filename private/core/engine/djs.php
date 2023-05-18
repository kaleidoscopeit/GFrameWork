<?php

/* js builder
 *
* This class contains all tool in order to address and build the dynamic
* java scripts related to a view.
*
*/

class _engine_js {

  static function init($_)
  {
    /* initialization */
    if(!$_->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');

  }

  static function build($_)
  {
    
    header('Content-type: text/javascript');
    echo implode('', $_->static[$_->CALL_URN]['js']['raw']);
  }
}
?>
