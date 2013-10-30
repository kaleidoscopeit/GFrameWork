<?php
// return javascript code by request
class _engine_djl {
  
  function get()
  { 
    $this->CALL_URI = explode('.', $this->CALL_URI);
    $djl_class      = array_shift($this->CALL_URI);

    switch ($djl_class) {
      case 'system' :
        $this->CALL_SOURCE = '../core/jss/all/'.
                             implode('/', $this->CALL_URI);
        break;
        
      case 'user' :
        $this->CALL_SOURCE = 'lib/js_plug/'.
                             implode ('/', $this->CALL_URI);
        break;
        
      case 'webget' :
        break;
        
      default ;
        die("Script not found.");
    }

    require('etc/config.php');

    if (!is_file($this->CALL_SOURCE.'.js'))
      die("Script not found.");

    $data = file_get_contents($this->CALL_SOURCE.'.js');

    if($this->settings['debug'] == true)
      echo $data;
      
    /* FIXME : this regex wipe out also http url, this is a bad behaviour */
    else echo trim
      (preg_replace("!\/\*[\S|\s]*?\*\/|\/\/.*?[\n\r]!", "", $data ), "\t\n");
  }
}
?>