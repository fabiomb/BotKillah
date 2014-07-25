<?php
session_start();

include_once('config.php');
include_once('db/db.php');

// Conecto con Twitter
require_once ('codebird-php.php');
\Codebird\Codebird::setConsumerKey($tw_consumer, $tw_secret); // static, see 'Using multiple Codebird instances'

$cb = \Codebird\Codebird::getInstance();
$cb->setToken($tw_token_a, $tw_token_b);

include('bot.php');

if (isset($_GET["id_str"])) {$id_str = $_GET["id_str"];}
if (isset($_GET["screen_name"])) {$screen_name = $_GET["screen_name"];}
if ( ! $screen_name ) {
    $usuario = get_random_bot();

    $id_str = $usuario['id_str'];
    $screen_name = $usuario['screen_name'];
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BotKillah!</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js" media="all" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css">
    </head>
    <body>
 <?php
        // lo marco como visto para no volver a usarlo
        $actualizado = "UPDATE usuario SET visto = 1 WHERE id_str = '$id_str'";  
        $db->sql_query($actualizado);       // put your code here
        
        $users = array_merge(get_followers($screen_name),get_friends($screen_name));
        foreach ( $users as $f ) {
            echo  '<a href="http://twitter.com/'.$f->screen_name.'" target="_blank" >'.$f->screen_name.'</a> <a href="?id_str='.$f->id_str.'&amp;screen_name='.$f->screen_name.'"><i class="icon-chevron-sign-right"></i></a><br />';

            $guarda = "INSERT INTO usuario (id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang) "
                    . "VALUES ('$f->id_str','$f->name', '$f->screen_name', '$f->location', '$f->description', "
                    . "'$f->followers_count', '$f->friends_count', '$f->created_at', '$f->statuses_count', '$f->lang' )";
            $db->sql_query($guarda);
            
            $relacion = "INSERT INTO relacion (id_str_inicio, id_str_destino) VALUES ('$id_str','$f->id_str')";
            
            $db->sql_query($relacion);
        }
        
        $next = get_random_bot();
        ?>
        <form action="index.php" method="GET">
            
            <input type="hidden" name="id_str" value="<?= $next['id_str'] ?>">
            <input type="hidden" name="screen_name" value="<?= $next['screen_name'] ?>">
            
            <input type="submit" value="Siguiente: <?= $next['screen_name'] ? $next['screen_name'] : 'NONE' ?>">
        
    </body>
</html>
