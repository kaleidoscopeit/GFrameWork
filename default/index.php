<?php

/* prints end microtime (for benchmarking purpuose) */
//echo 'microtime = '.( microtime (true) - $microtime);

include "../core/engine/main.php";

error_reporting( E_ALL ); 
//error_reporting(!E_NOTICE);
ini_set('log_errors', 1); 
ini_set('error_log', 'temp/php_error.log'); 
//ini_set('display_errors',1);


ob_start("ob_gzhandler");

/* get the path of called object if not previously declared
   (appens when the view is called in a nested execution) */
$source = array_keys($_GET);
$source = array_shift($source);           

/* redirect to the default page if the path of called object is malformed */
if (strpos ($source, '../') >- 1 OR $source == "")   
  header("location: ?views/main");

$tab     = -1;
$_       = new _($source);
$buffer  = $_->main(); 
$debug   = $_->settings['formatted_output'];

 

if (is_array($buffer))
  if ($debug)
    foreach ($buffer AS $key=>$value){
      if(substr($value, 0, 2) == '</') $tab--;

      echo str_repeat("\t" , ($tab<0 ? 0 : $tab));
      echo $value;
      echo "\n";

      if(substr($value, -2, 2) == '/>' || strpos($value, '</' . substr($value, 1, 2)) > 0) ;
      else if (substr($value, 0, 1) == '<' && substr($value, 0, 2) != '</') $tab ++;
    }
    
  else
    
    echo implode ('',$buffer);
  
else 
  echo $buffer;    
//echo memory_get_usage()

/* prints end microtime (for benchmarking purpuose) */
//echo 'microtime = '.( microtime (true) - $microtime);
?>
