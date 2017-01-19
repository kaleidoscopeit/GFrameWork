<?php

/* prints end microtime (for benchmarking purpuose) */
//echo 'microtime = '.( microtime (true) - $microtime);

include "../core/engine/main.php";

/* get the path of called framework object  */
$fwk_object = array_keys($_GET);
$fwk_object = array_shift($fwk_object);

/* redirect to the default page if the path of called object is malformed */
if (strpos ($fwk_object, '../') >- 1 OR $fwk_object == "")
header("location: ?views/main");

/* launch the framework execution */
$_       = new _($fwk_object); // <<<--- this is the ROOT class the big core!!!
$buffer  = $_->main();

/* Output the code built by the framework. */
$tab     = -1;
$debug   = $_->settings['formatted_output'];

/* If the code is an array starts the procedure to print every element of the
   array, otherwise prints the buffer as is. In case of multiline buffer and
   the debug feature is enabled the output will be formatted, otherwise
   everything will be compacted */
if (is_array($buffer)) {
  if ($debug) {
    foreach ($buffer AS $key=>$value) {
      if(substr($value, 0, 2) == '</') $tab--;

      echo str_repeat("\t" , ($tab<0 ? 0 : $tab));
      echo $value;
      echo "\r\n";

      if(substr($value, -2, 2) == '/>' || strpos($value, '</' . substr($value, 1, 2)) > 0);
      else if (substr($value, 0, 1) == '<' && substr($value, 0, 2) != '</') $tab ++;
    }
  }

  else {
    echo implode ('', $buffer);
  }
}

else {
  echo $buffer;
}
//echo memory_get_usage()

/* prints end microtime (for benchmarking purpuose) */
//echo 'microtime = '.( microtime (true) - $microtime);
?>
