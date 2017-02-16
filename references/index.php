<?php

error_reporting( E_ALL );
//error_reporting(!E_NOTICE);
ini_set('log_errors', 1);
//ini_set('error_log', 'temp/php_error.log');
ini_set('display_errors', 1);
/* enable commpression transfert */
ob_start("ob_gzhandler");

/* includes the index core */
include "../core/engine/index.php";
?>
