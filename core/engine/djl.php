<?php
// return javascript code by request
class _engine_djl {

  static function get($caller)
  {
    $caller->CALL_URI = explode('.', $caller->CALL_URI);
    $djl_class      = array_shift($caller->CALL_URI);

    switch ($djl_class) {
      case 'system' :
        $caller->CALL_SOURCE = '../core/jss/all/'.
                             implode('/', $caller->CALL_URI);
        break;

      case 'user' :
        $caller->CALL_SOURCE = 'lib/js_plug/'.
                             implode ('/', $caller->CALL_URI);
        break;

      case 'webget' :
        break;

      default ;
        die("Script not found.");
    }

    require('etc/config.php');

    if (!is_file($caller->CALL_SOURCE.'.js'))
      die("Script not found.");

    $data = file_get_contents($caller->CALL_SOURCE.'.js');

    if($caller->settings['debug'] == true)
      echo $data;

    /* FIXME : this regex wipe out also http url, this is a bad behaviour */
    else echo trim
      (preg_replace("!\/\*[\S|\s]*?\*\/|\/\/.*?[\n\r]!", "", $data ), "\t\n");
  }
}
?>
