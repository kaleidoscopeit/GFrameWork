<?php

/* css builder
 *
* This class contains all tool in order to address and build
* css's realted to a view.
*
*/

class _engine_css {

  static function init($_)
  {
    /* initialization */
    if(!$_->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }

  static function build($_)
  {
    $_->CALL_URN = explode('.', $_->CALL_URN);
    $obj_class   = array_shift($_->CALL_URN);

    $ftimes         = array();

//    if(isset($caller->static[$caller->CALL_URN]['css']))
//      $css_static = $caller->static[$caller->CALL_URN]['css'];

//    $style_prefix   = $css_static['prefix'];
//    $style_registry = $css_static['registry'];
//    $expires         = 60*3;
//    unset($caller->static[$caller->CALL_URN]['css']);

    /* gets the file list and modify time */
    switch($obj_class){
      case 'webgets' :
        $css_files = array();
        @array_map(function($package) use (&$css_files, $_){
          if($package == '.' || $package == '..') return;
          return array_map(function($file) use ($package, &$css_files, $_){
              if($file == '.' || $file == '..') return;
              $css_file = $_->WEBGETS_PATH . $package . '/css/' . $file;
              $css_files[$css_file] = filemtime($css_file);
            }, (array)scandir($_->WEBGETS_PATH . $package . '/css'));
         }, (array)scandir($_->WEBGETS_PATH));

        $ftimes = $css_files;
        sort($ftimes , SORT_NUMERIC);
        $ftimes = array_pop($ftimes);

        break;
    }


    header('Content-type: text/css');
    header("Cache-Control: maxage=0");


    switch($obj_class){
      case 'webgets' :
        @array_map(function($file){
          echo preg_replace('/[\t\n]+/', '', file_get_contents($file));
        }, array_keys($css_files));
        break;

        case 'view' :
          $f = $_->APP_PATH . '/views/' . implode('.', $_->CALL_URN) . '/_this.css';
          if (!is_file($f)) die;
          $fp = fopen($f , 'r'); 
          fpassthru($fp);
          fclose($fp);
          //die;
          break;

//      default :
//        @array_map(function($key, $value) use ($style_prefix){
//          echo '.' . $style_prefix . $key . '{'.$value."}";},
//          array_keys($style_registry), $style_registry);

        break;

    }


  }

}

?>
