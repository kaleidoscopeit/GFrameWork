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

    $ftimes          = array();
    $source_url      ='views/' . $source_url . '/_this.xml';
    $this->CALL_UUID = array_keys($_GET);
    $this->CALL_UUID = $this->CALL_UUID[1];
    $css_static      = $this->static[$this->CALL_UUID]['css'];
    $ccs_files       = $css_static ['files'];
    $style_prefix    = $css_static ['prefix'];
    $style_registry  = $css_static ['registry'];
        
    // get the most recent date of each file and compare them whith the 
    // one in browser cache
    $ftimes[] = filemtime($source_url);

    foreach($ccs_files as $k=>$v){
      $ftimes[] = filemtime($k);
    }
    
    sort($ftimes, SORT_NUMERIC);
    $ftimes = array_pop($ftimes);
    
    // Checking if the client is validating his cache and if it is current.
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
        && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $ftimes));
    //  header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 304);
         
    else
    //  header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 200);
    
    $expires = 60*60*24*14;
    //header("Pragma: public");
    //header("Cache-Control: maxage=".$expires);
    //header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
   

    foreach($ccs_files as $k=>$v){
      echo preg_replace('/[\t\n]+/', '', file_get_contents($k));

    }    


    foreach((array) @$style_registry as $key => $value){
      echo '.' . $style_prefix . $key . '{'.$value."}\n";      

    }

    unset($this->static[$this->CALL_UUID]['css']);

  }
 
}

?>