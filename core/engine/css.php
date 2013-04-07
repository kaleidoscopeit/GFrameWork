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
  	if(!$this->CALL_TARGET) die ('TARGET_NOT_SPECIFIED');
  }
  
  function build($source_url)
  {
    $ftimes         = array();
    $source_url     ='views/' . $source_url . '/_this.xml';
    $CALL_UUID 			= array_pop(array_keys($_GET));
    $css_static     = $this->static[$CALL_UUID]['css'];
    $css_files      = $css_static['files'];
    $style_prefix   = $css_static['prefix'];
    $style_registry = $css_static['registry'];
    $expires 				= 60*3;

		unset($this->static[$CALL_UUID]['css']);
		
		/* gets the file list and modify tyme */
		switch($this->CALL_TARGET){
			case 'webgets' :
				$css_files = array();
				array_map(function($package) use (&$css_files){						
					return @array_map(function($file) use ($package, &$css_files){
						if($file == '.' || $file == '..') return; 
						$css_file = $this->WEBGETS_PATH . $package . '/css/' . $file;
						$css_files[$css_file] = filemtime($css_file);					
					}, scandir($this->WEBGETS_PATH . $package . '/css'));
			 	}, scandir($this->WEBGETS_PATH));
			 	

				$ftimes = $css_files;
  	    sort($ftimes , SORT_NUMERIC);
		    $ftimes = array_pop($ftimes);

				break;
			
			case 'view' :
				$css_files[$source_url] = true;
				
				$ftimes = array_map(function($file){						
					return filemtime($file);
				},array_keys($css_files));

  	    sort($ftimes , SORT_NUMERIC);
		    $ftimes = array_pop($ftimes);
		       				 
				break;
			
			default :
				echo 'WRONG_TARGET';
		}


    header('Content-type: text/css');
    header("Pragma: public");
    header("Cache-Control: maxage=".$expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
    
           
    // Checking if the client is validating his cache and if it is current.
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
        && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $ftimes)) {
      //header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 304);
    }
         
    else 
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ftimes).' GMT', true, 200);
  
  
		switch($this->CALL_TARGET){
			case 'webgets' :
				array_map(function($file){
		      echo preg_replace('/[\t\n]+/', '', file_get_contents($file));
		    }, array_keys($css_files));
				break;

				
			case 'view' :
				array_map(function($key, $value) use ($style_prefix){
					echo '.' . $style_prefix . $key . '{'.$value."}";
				} , array_keys($style_registry), $style_registry);
		      
				array_map(function($file){
		      echo preg_replace('/[\t\n]+/', '', file_get_contents($file));
		    }, array_keys($css_files));

				break;		  
		  
		}


  }
 
}

?>