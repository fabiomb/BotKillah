<?php 
include_once('config.php');
include_once('db/db.php');

// Conecto con Twitter
require_once ('codebird-php.php');
\Codebird\Codebird::setConsumerKey($tw_consumer, $tw_secret); // static, see 'Using multiple Codebird instances'

$cb = \Codebird\Codebird::getInstance();
$cb->setToken($tw_token_a, $tw_token_b);

include('bot.php');

$trending = trending($cb, $woeid);

// El index sirve para disparar la primer semilla de búsqueda
// detectar si hay al menos un bot para iniciar, caso contrario consultar por uno

// por el momento para seguir como estaba:

// verifico si la base está vacía

// caso afirmativo imprimo form de carga de primer objetivo

// caso negativo tomo uno al azar

// si hay disponible comienzo

// si no hay disponible aclaro que ya estan todos analizados y que no quedan bots por explorar, 
// form de carga por si hay uno nuevo

    $usuario = get_random_bot();

    if ($usuario)
    {
        // tomo el bot semilla para comenzar
        $id_str = $usuario['id_str'];
        $screen_name = $usuario['screen_name'];        
    }
    else
    {
        // se quedó sin bots, necesita uno nuevo
        
    }
    // obtengo estadísticas generales
    $stats = live_stats ();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BotKillah!</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
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
          <a class="navbar-brand" href="index.php">BotKillah</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <div class="container">
        <h1>BotKillah</h1>
        
    <div class="row">
        <div class="col-sm-12">

      </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
        <h2>Comenzar a investigar</h2>
        <form action="spider.php" method="GET">
            
            <input id="id_str" type="hidden" name="id_str" value="<?= $usuario['id_str'] ?>">
            <input id="screen_name" type="hidden" name="screen_name" value="<?= $usuario['screen_name'] ?>">
            
            <input id="next" type="submit" value="Comenzar: <?= $usuario['screen_name'] ? $usuario['screen_name'] : 'NONE' ?> »" class="btn btn-default">
            </form>
        </div>
        <div class="col-sm-6">
        <h2>Insertar semilla</h2>

        <form action="semilla.php" method="GET">
            <input id="screen_name" type="text" name="screen_name">
            
            <input id="next" type="submit" value="Insertar semilla »" class="btn btn-default">
        </form>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <br /><br />
            <h2>Stats</h2>
            Usuarios sin analizar: <?php echo $stats->sinanalizar;?><br />
            Usuarios excluídos: <?php echo $stats->excluidos;?><br />
            Bots detectados sin analizar: <?php echo $stats->esbot;?><br />
            Bots detectados analizados: <?php echo $stats->esbotanalizado;?><br />
            Total Bots: <?php echo $stats->bots;?><br /><br /><br />
            
            Tags de filtro: <br /><em><?php echo implode(",", $tags); ?></em><br /><br />
            
            Localidad: <?php echo $ciudad;?><br /><br />
        </div>
        <div class="col-sm-6  pre-scrollable">
        <br /><br />
        <h2>Trending</h2>
        <?php

          $limit = $trending->rate["limit"];
          $remaining = $trending->rate["remaining"];
          $reset = $trending->rate["reset"];
        foreach  ($trending as $trend)
        {
          break;
        }
          $as_of = $trend->as_of;
          $created_at = $trend->created_at;

          echo "Fecha: ". $created_at."<br />";

           foreach  ($trend->trends as $trendy)
            {   
              $name = $trendy->name;
              $url = $trendy->url;
              $promoted_content = $trendy->promoted_content;
              $query = $trendy->query;
              $tweet_volume = $trendy->tweet_volume;
              echo "<a href=\"$url\">$name</a> ";
              if ($tweet_volume) {echo "($tweet_volume) ";}
              if ($promoted_content) {echo "(Promoted) ";}
              echo '<a href="research.php?q='.$query.'"><i class="icon-chevron-sign-right"></i></a>';
              echo "<br />";
          }
          echo "<br />";
          echo "Remaining: $remaining / $limit";


        

        //   print_r ( $trending );
        ?>
        </div>
    </div>        
    </body>
</html>
