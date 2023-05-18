
<?php
/* Global paths */
$proj_name = basename(dirname(__FILE__));
$proj_path = "../../private/$proj_name";
$core_path = "../../private/core";

/* Log Settings */
error_reporting( E_ALL ^ E_DEPRECATED );
ini_set('log_errors', 1);
#ini_set('error_log', "$pvt_path/temp/php_error.log");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/* Enable commpression transfert */
#ob_start("ob_gzhandler");
#echo "b";die;


/* Launch the core engine */
include "$core_path/engine/index.php";

?>
