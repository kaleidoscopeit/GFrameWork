<?php
// return javascript code on demand
class _engine_djl {

  static function get($_)
  {
    $_->CALL_URN = explode('.', $_->CALL_URN);
    $djl_class   = array_shift($_->CALL_URN);

    switch ($djl_class) {
      case 'system' :
        $_->CALL_SOURCE = '../core/lib/jss/all/'.
                             implode('/', $_->CALL_URN);
        break;

      case 'user' :
        $_->CALL_SOURCE = 'lib/jss/'.
                             implode ('/', $_->CALL_URN);
        break;

      case 'webgets' :
        /* get the js of the root webget */
        $root_wbg = $_->WEBGETS_PATH . 'root/js/' . array_pop($_->CALL_URN);
        if (!is_file($root_wbg . '.js')) die ('No root script');

        $fp = fopen($root_wbg . '.js' , 'r'); 
        fpassthru($fp);
        fclose($fp);

        /* get the js of the other webgets */
        foreach (glob($_->WEBGETS_PATH . '*', GLOB_ONLYDIR) as $dir) {
          if(str_ends_with($dir, 'root')) continue;
          foreach (glob($dir . '/js/*.js') as $script) {
            $fp = fopen($script, 'r'); 
            fpassthru($fp);
            fclose($fp);
          }
        }
        break;

      case 'view' :
        $f = $_->APP_PATH . '/views/' . implode('.', $_->CALL_URN) . '/_this.js';
        if (!is_file($f)) die;
        $fp = fopen($f , 'r'); 
        fpassthru($fp);
        fclose($fp);
        //die;
        break;

      default ;
        die("Script not found.");
    }
    return;
    if (is_file($_->CALL_SOURCE)) {
      $fp = fopen($_->CALL_SOURCE, 'r');      
      
      header("Content-Type: text/javascript");
      //header("Last-Modified: " . gmdate("D, d M Y H:i:s GMT", filemtime($_->CALL_SOURCE)));
      fpassthru($fp);
      return;  
    }
      



    if($_->settings['debug'] == false) {

      return;
    }    
    
    $data = file_get_contents($_->CALL_SOURCE);

    /* FIXME : this regex wipe out also http url, this is a bad behaviour */
    echo trim(preg_replace("!\/\*[\S|\s]*?\*\/|\/\/.*?[\n\r]!", "", $data ), "\t\n");
  }
}
?>
