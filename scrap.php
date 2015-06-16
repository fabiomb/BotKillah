<?php
include_once('config.php');
include_once('db/db.php');
include_once('bot.php');
// Stats de palabras más usadas, números y estadísticas generales

$pagina = get_CURL ("https://twitter.com/flor2356");
$document = new DOMDocument;
libxml_use_internal_errors(true);
$document->loadHTML($pagina);
$xpath = new DOMXPath($document);

$tweets = $xpath->query("//p[@class='TweetTextSize TweetTextSize--16px js-tweet-text tweet-text']");

$tags = array ("#cienrazonesparaNOvotaraMACRI","cienrazonesparanovotaramacri.com", "Macri");
$cuenta = 0;

foreach ($tweets as $tweet)
{
    $string = $tweet->nodeValue;
    $largo = strlen ($string);
    foreach ($tags as $tag)
    {
        $posicion = strpos($string, $tag);
        if ($posicion)
        {
            $cuenta++;
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
  
        <title></title>

    </head>
    <body>

        
        <h2>Scrap</h2>
        
<?php

    
echo $cuenta;

?>

    </body>
</html>

