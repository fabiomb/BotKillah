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
if (isset($_GET["esbot"])) {$esbot = $_GET["esbot"];}

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
        <title>BotKillah!<?= $screen_name ? ' - '.$screen_name : '' ?></title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script>
          /*$(document).ready(function(){

            var next = function() {
              if ( $('#id_str').val() != "") {
                $('#next').click();
              }

            }

            var found = $('.alert').text().search(/Rate limit/i);

            if ( found != -1 ) {
              setTimeout(function(){
                
                location.reload();

              }, 60000 * 15);

            } else {
              next();
            }

          });*/
        </script>
    </head>
    <body>
 <?php
    // marco como leído
    mark_as_viewed($id_str);
    if ($esbot == "1" ) {mark_as_bot($id_str);};
    
    if ($screen_name <> "")
    {
        // tomo los tuits, prueba típica de bots para análisis posterior
        $tuits = get_tuits ($screen_name);
        // guardo los tuits del usuario analizado
        save_tuits ($id_str, $screen_name, $tuits);
        // tomo followers y amigos
        $users = array_merge(get_followers($screen_name),get_friends($screen_name));        
        // ordeno y limpio duplicados
        $users = array_unique($users,SORT_REGULAR);

    // la lista de users la tengo que cruzar con los que ya vi, descartar el que está visto, dejar el que no
    foreach ( $users as $foo ) {
    if (!existe_user($foo->id_str))
            {
                $usuarios[]=$foo;
            }
    }    
    $users = $usuarios;
        
    foreach ( $users as $f ) {
        $esbot = 0; // default
        if (count($tags)<> 0)
        {
            // si hay tags cargados tomo el timeline de cada usuario y cuento coincidencias para determinar bot.
            $cuenta = scrap_user_tl ($f->screen_name, $tags);
            if ($cuenta >= $limite_tags) {$esbot = 1;}
        }
        
        echo '<a href="http://twitter.com/'.$f->screen_name.'" target="_blank" >'.$f->screen_name.'</a> (FO:'.$f->followers_count.' - FC:'.$f->friends_count.' - TW:'.$f->statuses_count.')';
        echo '<a href="?id_str='.$f->id_str.'&amp;screen_name='.$f->screen_name.'"><i class="icon-chevron-sign-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
        echo ' Probabilidad: '.$cuenta. '&nbsp;&nbsp;';
        if ($cuenta >= $limite_tags) {echo '<strong>BOT</strong>&nbsp;&nbsp;';}
        echo '<a href="?id_str='.$f->id_str.'&amp;screen_name='.$f->screen_name.'&esbot=1"><i class="icon-android"></i></a>';
        echo '<br />';

        // agrego condición de fecha para filtrar más rápido
       /* if ((strpos($f->created_at, '2014') <> '0') or (strpos($f->created_at, '2015') <> '0'))
        {*/
            // si no existe lo guardo
            save_if_not_exist($f, $esbot);
            // guardo su relación
            save_relation($id_str,$f->id_str);
        /*}*/
    }
    }        
    // busco uno siguiente al azar
    $next = get_random_bot();
?>
        <form action="index.php" method="GET">
            
            <input id="id_str" type="hidden" name="id_str" value="<?= $next['id_str'] ?>">
            <input id="screen_name" type="hidden" name="screen_name" value="<?= $next['screen_name'] ?>">
            
            <input id="next" type="submit" value="Siguiente: <?= $next['screen_name'] ? $next['screen_name'] : 'NONE' ?>">
        
    </body>
</html>
