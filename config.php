<?php
if(preg_match('/^config.php/',$_SERVER['PHP_SELF']))
{ header("HTTP/1.0 404 Not Found"); 
}

// --------- MYSQL Config --------- 
$dbhost = "localhost";   // Servidor MySQL
$dbuname = "root";
$dbpass = "";
$dbname = "botkillah";
$dbtype = "mysqli";     // mysql4 or MySQL or mysqli


/* Login por Facebook */
define('YOUR_APP_ID', '750751804952921');
define('SECRETFB', '6dcec01304b0fdfaad4ef1f323dc2dc3');


date_default_timezone_set('America/Argentina/Buenos_Aires');

?>