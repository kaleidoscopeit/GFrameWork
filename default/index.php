<?php

/* prints end microtime (for benchmarking purpuose) */
//echo 'microtime = '.( microtime (true) - $microtime);

include "../core/engine/main.php";

ini_set('display_errors',1); 
//error_reporting(E_ALL);
//error_reporting(!E_NOTICE);
ob_start("ob_gzhandler");

$tab     = -1;
$_       = new _();
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
