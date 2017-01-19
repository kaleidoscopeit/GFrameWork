<?php

/* js builder
 *
* This class contains all tool in order to address and build
* the java scripts related to a view.
*
*/

class _engine_js {

  static function init($caller)
  {
    /* initialization */
    if(!$caller->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');

  }

  static function build($caller)
  {
    header('Content-type: text/javascript');
    echo implode('', $caller->static[$caller->CALL_URI]['js']['raw']);
  }
}
?>
