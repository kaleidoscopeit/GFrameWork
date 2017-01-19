<?php

/* css builder
 *
* This class contains all tool in order to address and build
* css's realted to a view.
*
*/

class _engine_css {

  static function init($caller)
  {
    /* initialization */
    if(!$caller->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }

  static function build($caller)
  {
    $ftimes         = array();

    if(isset($caller->static[$caller->CALL_URI]['css']))
      $css_static = $caller->static[$caller->CALL_URI]['css'];

    $style_prefix   = $css_static['prefix'];
    $style_registry = $css_static['registry'];
    $expires         = 60*3;

    unset($caller->static[$caller->CALL_URI]['css']);

    /* gets the file list and modify time */
    switch($caller->CALL_TARGET){
      case 'webgets' :
        $css_files = array();
        $_ = $caller;
        @array_map(function($package) use (&$css_files, $_){
          if($package == '.' || $package == '..') return;
          return array_map(function($file) use ($package, &$css_files, $_){
              if($file == '.' || $file == '..') return;
              $css_file = $_->WEBGETS_PATH . $package . '/css/' . $file;
              $css_files[$css_file] = filemtime($css_file);
            }, (array)scandir($_->WEBGETS_PATH . $package . '/css'));
         }, (array)scandir($caller->WEBGETS_PATH));

        $ftimes = $css_files;
        sort($ftimes , SORT_NUMERIC);
        $ftimes = array_pop($ftimes);

        break;
    }


    header('Content-type: text/css');
/*//    header("Pragma: public");
//    header("Cache-Control: maxage=".$expires);
//    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');


    // Checking if the client is validating his cache and if it is current.
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $ftimes)) {
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 304);
    }

    else
//      header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 200);*/

header("Cache-Control: maxage=0");


    switch($caller->CALL_TARGET){
      case 'webgets' :
        @array_map(function($file){
          echo preg_replace('/[\t\n]+/', '', file_get_contents($file));
        }, array_keys($css_files));
        break;

      default :
        @array_map(function($key, $value) use ($style_prefix){
          echo '.' . $style_prefix . $key . '{'.$value."}";},
          array_keys($style_registry), $style_registry);

        break;

    }


  }

}

?>
