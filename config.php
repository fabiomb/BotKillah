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

$tw_consumer = "siZSIfxOCwpRTzIiztUDvbKoO";
$tw_secret = "Qowedk8gJMswUG0vEMLBJOcDxcvt7EF7FULiIfikk4l0xnFZ08";
$tw_token_a ="2443260073-3C6xBGPv6rpXdHbC1DB4ECcIlLWz5X5JgBOOiJQ";
$tw_token_b = "hda2lWBSD7rSNO9xD4YoQ1I19w6DgE2pczgixioTrOTPH";


date_default_timezone_set('America/Argentina/Buenos_Aires');

?>