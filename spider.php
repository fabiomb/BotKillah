<?php 
set_time_limit(2400);
session_start();
ob_start();


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
        <style>
            /* Move down content because we have a fixed navbar that is 50px tall */
body {
  padding-top: 50px;
}


/*
 * Global add-ons
 */

.sub-header {
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

/*
 * Top navigation
 * Hide default border to remove 1px line.
 */
.navbar-fixed-top {
  border: 0;
}
        </style>
    </head>
    <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">BotKillah</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <div class="container">
        <h1>Inspeccionando @<?php echo $screen_name;?></h1>
        
        <div class="row">
            <div class="col-sm-12">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Followers</th>
                  <th>Followings</th>
                  <th>Tweets</th>
                  <th>Probability</th>
                  <th>Inspect</th>
                  <th>Mark as Bot</th>
                </tr>
              </thead>
              <tbody>
 <?php

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

    if (count($users) > 1)   
    {
        // si pude traer la data, marco como leído
        // marco como leído
        mark_as_viewed($id_str);
    }
        
    // la lista de users la tengo que cruzar con los que ya vi, descartar el que está visto, dejar el que no
    foreach ( $users as $foo ) {
    if (!visto_user($foo->id_str))
            {
                $usuarios[]=$foo;
            }
    }    
    $users = $usuarios;
    $explorados = 0;    
    foreach ( $users as $f ) {
        $esbot = 0; // default
        $excluir = 0; //default
        $explorados++; // tan sólo par contar los de esta pasada
        if (count($tags)<> 0)
        {
            // si hay tags cargados tomo el timeline de cada usuario y cuento coincidencias para determinar bot.
            $cuenta = scrap_user_tl ($f->screen_name, $tags);
            // decido si es bot
            if ($cuenta >= $limite_tags) 
                {
                    $esbot = 1;
                }
                else
                {
                    $excluir = 1; // no tiene un timeline infectado, se excluye
                }
        }
        echo '<tr>';
        echo '<td><a href="http://twitter.com/'.$f->screen_name.'" target="_blank" >'.$f->screen_name.'</a></td>';
        echo '<td>'.$f->followers_count.'</td><td>'.$f->friends_count.'</td><td>'.$f->statuses_count.'</td>';
        echo '<td>'.$cuenta. '</td>';
        echo '<td><a href="spider.php?id_str='.$f->id_str.'&amp;screen_name='.$f->screen_name.'"><i class="icon-chevron-sign-right"></i></a></td>';
        echo '<td>';
        if ($cuenta >= $limite_tags) {echo '<strong>BOT</strong>&nbsp;&nbsp;';}
        echo '<a href="spider.php?id_str='.$f->id_str.'&amp;screen_name='.$f->screen_name.'&esbot=1"><i class="icon-android"></i></a>';
        echo '</td></tr>';

        // agrego condición de fecha para filtrar más rápido
       /* if ((strpos($f->created_at, '2014') <> '0') or (strpos($f->created_at, '2015') <> '0'))
        {*/
            // si no existe lo guardo
            save_if_not_exist($f, $esbot, $excluir);
            // guardo su relación
            save_relation($id_str,$f->id_str);
            
                ob_end_flush();
                ob_flush();
                flush(); // SE IMPRIMEEEEEEEEEEE
                ob_start();
        /*}*/
    }
    }        
    // busco uno siguiente al azar
    $next = get_random_bot();
    
    // obtengo estadísticas generales
    $stats = live_stats ();
   
?>
              </tbody>
    </table>
      </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
        <form action="spider.php" method="GET">
            
            <input id="id_str" type="hidden" name="id_str" value="<?= $next['id_str'] ?>">
            <input id="screen_name" type="hidden" name="screen_name" value="<?= $next['screen_name'] ?>">
            
            <input id="next" type="submit" value="Siguiente: <?= $next['screen_name'] ? $next['screen_name'] : 'NONE' ?> »" class="btn btn-default">
        </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                Usuarios incluídos en esta instancia: <?php echo $explorados;?><br />
                Usuarios sin analizar: <?php echo $stats->sinanalizar;?><br />
                Usuarios excluídos: <?php echo $stats->excluidos;?><br />
                Bots detectados sin analizar: <?php echo $stats->esbot;?><br />
                Bots detectados analizados: <?php echo $stats->esbotanalizado;?><br />
                Total Bots: <?php echo $stats->bots;?><br /><br /><br />
            </div>
        </div>
    </body>
</html>
<?php
ob_end_flush();  
?>