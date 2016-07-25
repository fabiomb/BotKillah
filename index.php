<?php 

include_once('config.php');
include_once('db/db.php');
include('bot.php');

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
          <a class="navbar-brand" href="#">BotKillah</a>
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
        Comenzar a investigar
        <form action="spider.php" method="GET">
            
            <input id="id_str" type="hidden" name="id_str" value="<?= $usuario['id_str'] ?>">
            <input id="screen_name" type="hidden" name="screen_name" value="<?= $usuario['screen_name'] ?>">
            
            <input id="next" type="submit" value="Comenzar: <?= $usuario['screen_name'] ? $usuario['screen_name'] : 'NONE' ?> »" class="btn btn-default">
            </form>
        </div>
        <div class="col-sm-6">
        Insertar semilla

        <form action="semilla.php" method="GET">
            <input id="screen_name" type="text" name="screen_name">
            
            <input id="next" type="submit" value="Insertar semilla »" class="btn btn-default">
        </form>

        </div>
    </div>
        <div class="row">
            <div class="col-sm-12">
                <br /><br />
                Usuarios sin analizar: <?php echo $stats->sinanalizar;?><br />
                Usuarios excluídos: <?php echo $stats->excluidos;?><br />
                Bots detectados sin analizar: <?php echo $stats->esbot;?><br />
                Bots detectados analizados: <?php echo $stats->esbotanalizado;?><br />
                Total Bots: <?php echo $stats->bots;?><br /><br /><br />
            </div>
        </div>        
    </body>
</html>
