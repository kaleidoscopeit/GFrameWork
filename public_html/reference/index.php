<?php
/* Log Settings */
ini_set('log_errors', 1);
error_reporting( E_ALL ^ E_DEPRECATED );

/* Display errors to the client */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/* Log errors in 'temp' a file */
//ini_set('error_log', '../../private/' . basename(__DIR__) . '/temp/php_error.log');

/* Enable commpression transfer */
#ob_start("ob_gzhandler");
#echo "b";die;

/* Launch the core engine */
include "../../private/core/engine/index.php";

?>
