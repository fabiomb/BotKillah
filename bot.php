<?php


function get_random_bot() {
    global $db;
    // entrada por default, comienza el spider buscando uno que esté disponible
    $siguiente = "SELECT * FROM `usuario` WHERE visto = 0 and esbot = 1  and excluir = 0 limit 0,1";  
    $resultadosig = $db->sql_query($siguiente);        
    return $db->sql_fetchrow($resultadosig);
}
function visto_user($id_str)
{
    // devuelve un usuario que ya se visitó o no se debe visitar
    // para no volver a pasar una y otra vez por los mismos
    global $db;
    $siguiente = "SELECT excluir, esbot, visto FROM `usuario` WHERE id_str = '$id_str' limit 0,1";  
    $resultadosig = $db->sql_query($siguiente);      
    $row = $db->sql_fetchrow($resultadosig);
    if (($row["visto"] == 1) or ($row["excluir"] == 1))
    {
        $estado = TRUE;
    }
    else 
    {
        $estado = FALSE;
    }
return $estado;
}
function get_friends ( $screen_name ) {
    global $cb;

    $nextCursor = "-1";
    $i = 0;

    $parameters = array(
        'cursor' => $nextCursor,
        'count' =>200,
        'screen_name'=> $screen_name
    );

    $followers = array();

    while ( $nextCursor ) {
        $i++;
        $result = $cb->friends_list($parameters);
        $old_cursor = $nextCursor;
        $nextCursor = $result->next_cursor_str;

        handle_errors($result);
        
        if (($nextCursor == $old_cursor) and ($cuenta > 2)) {
            echo "Se repite!";
            $nextCursor = NULL; // se repite
        }

        if (($nextCursor == "0") or  ($nextCursor == "-1")) {$nextCursor = NULL;} // vacío

        if (is_array($result->users))
        {
        $followers = array_merge($followers, $result->users);
        }
        // Asi no bardea el limite
        if ( $i > 3 ) { break; }
    }

    return $followers;
}


function get_tuits ($screen_name)
{
    global $cb;

    $nextCursor = "-1";
    $i = 0;
    // tomo 50 tuits, más no hace falta, se debería parametrizar
    $parameters = array(
        'cursor' => $nextCursor,
        'count' =>50,
        'screen_name'=> $screen_name
    );    
    
        $result = $cb->statuses_userTimeline($parameters);
        
    return $result;
}
function get_user($screen_name)
{
    global $cb;

    $parameters = array(
        'screen_name'=> $screen_name
    );    
    
        $result = $cb->users_show($parameters);
        
    return $result;
}





function get_followers ( $screen_name ) {
    global $cb;

    $nextCursor = "-1";        
    $i = 0;

    $parameters = array(
        'cursor' => $nextCursor,
        'count' =>200,
        'screen_name'=> $screen_name
    );

    $followers = array();

    while ( $nextCursor ) {
        $i++;
        $result = $cb->followers_list($parameters);
        $old_cursor = $nextCursor;
        $nextCursor = $result->next_cursor_str;
        
        handle_errors($result);
       
        if (($nextCursor == $old_cursor) and ($cuenta > 2)) {
            echo "Se repite!";
            $nextCursor = NULL; // se repite
        }

        if (($nextCursor == "0") or  ($nextCursor == "-1")) {$nextCursor = NULL;} // vacío

        if (is_array($result->users))
        {
            $followers = array_merge($followers, $result->users);
        }
        // Asi no bardea el limite
        if ( $i > 6 ) { break; }
    }

    return $followers;
}

function handle_errors($result) {
    if ($result->errors) {
        echo '<div class="alert alert-warning"><strong>Error!</strong> '.$result->errors[0]->message."</div><br />";
    }
}
function save_if_not_exist($f, $esbot, $excluir) {
    global $db;
    
    // consulto, si existe actualizo estado de bot y exclusión
        $resultado = $db->sql_query("SELECT id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang, esbot, excluir"
                . " FROM usuario WHERE id_str = '$f->id_str' and screen_name = '$f->screen_name'");
        $desdebase = FALSE;
        while ($row = $db->sql_fetchrow($resultado)) 
	{
            $desdebase = TRUE;
            
        }   
    if ($desdebase)
    {
        $guarda = "UPDATE usuario SET esbot = '$esbot', excluir = '$excluir'  WHERE id_str = '$f->id_str' and screen_name = '$f->screen_name'";
    }
    else
    {
        $guarda = "INSERT INTO usuario (id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang, esbot, excluir) "
                . "VALUES ('$f->id_str','$f->name', '$f->screen_name', '$f->location', '$f->description', "
                . "'$f->followers_count', '$f->friends_count', '$f->created_at', '$f->statuses_count', '$f->lang', '$esbot', '$excluir' )";        
    }
    // si no existe se inserta
    $db->sql_query($guarda);
    //echo $guarda;

}

function save_tuits($id_str, $screen_name,$result) {
    global $db;
    
        foreach  ($result as $tuit)
        {   
          $fecha = $tuit->created_at;
          $texto = $tuit->text;
          $id_tuit = $tuit->id_str;
          $favorited =  $tuit->favorited;
          $retweeted =  $tuit->retweeted;
          if ($texto <> "")
          {
                $guarda = "INSERT INTO tuit (id_str, screen_name, fecha, texto, id_tuit, favorited, retweeted) "
                        . "VALUES ('$id_str','$screen_name', '$fecha', '$texto', '$id_tuit', "
                        . "'$favorited', '$retweeted' )";
                //echo $guarda . "<br /><br />";
                $db->sql_query($guarda);
          }
        }


}

function save_relation ($from, $to ) {
    global $db;
    $relacion = "INSERT INTO relacion (id_str_inicio, id_str_destino) VALUES ('$from','$to')";
    $db->sql_query($relacion);
}

function mark_as_viewed($id_str) {
    global $db;
    // lo marco como visto para no volver a usarlo
    $actualizado = "UPDATE usuario SET visto = 1 WHERE id_str = '$id_str'";  
    $db->sql_query($actualizado);
}

function mark_as_bot($id_str) {
    global $db;
    // lo marco como visto para no volver a usarlo
    $actualizado = "UPDATE usuario SET esbot = 1 WHERE id_str = '$id_str'";  
    $db->sql_query($actualizado);
}

function scrap_user_tl ($screen_name, $tags)
{
    // devuelve cierta data de tuits del usuario para identificar manualmente si es o no y no consumir API
    
    $pagina = get_CURL ("https://twitter.com/".$screen_name);
    $document = new DOMDocument;
    libxml_use_internal_errors(true);
    $document->loadHTML($pagina);
    $xpath = new DOMXPath($document);

    $tweets = $xpath->query("//p[@class='TweetTextSize TweetTextSize--16px js-tweet-text tweet-text']");

    
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
    
    
    return $cuenta;
}


function get_CURL ($url)
{
    $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

    $ch = curl_init();

    $curl_opt = array(
        CURLOPT_FOLLOWLOCATION  => 1,
        CURLOPT_HEADER      => 0,
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_USERAGENT   => $curlopt_useragent,
        CURLOPT_URL       => $url,
        CURLOPT_TIMEOUT         => 5,
        CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
    );

    curl_setopt_array($ch, $curl_opt);

    

    
    $content = curl_exec($ch);

        if($content === false)
    {
        echo 'Curl error: ' . curl_error($ch);
    }

    if (!is_null($curl_info)) {
        $curl_info = curl_getinfo($ch);
        
    }

    curl_close($ch);
    return $content;
}

function live_stats ()
{
    global $db;
    $row = "";
    $sinanalizar = "";
    $esbot ="";
    $esbotanalizado = "";
    $excluidos = "";

    $query_total = "SELECT count(*) as cuenta FROM `usuario` WHERE esbot = 1 ";
    $resultado_total = $db->sql_query($query_total);
    
        while ($row = $db->sql_fetchrow($resultado_total)) 
	{
            $bots = $row["cuenta"];
        }     
    
    $query_grilla_status = "SELECT count(*) as cuenta, visto, esbot, excluir FROM `usuario` group by visto, esbot, excluir";
    $resultado_grilla = $db->sql_query($query_grilla_status);

        while ($row = $db->sql_fetchrow($resultado_grilla)) 
	{
            if (($row["visto"] == 0) and ($row["esbot"] == 0) and ($row["excluir"] == 0)) {$sinanalizar = $row["cuenta"];}
            if (($row["visto"] == 0) and ($row["esbot"] == 0) and ($row["excluir"] == 1)) {$excluidos = $row["cuenta"];}
            if (($row["visto"] == 0) and ($row["esbot"] == 1) and ($row["excluir"] == 0)) {$esbot = $row["cuenta"];}
            if (($row["visto"] == 1) and ($row["esbot"] == 1) and ($row["excluir"] == 0)) {$esbotanalizado = $row["cuenta"];}
        }     
        
    $respuesta = new stdClass();
    $respuesta->bots = $bots;
    $respuesta->sinanalizar = $sinanalizar;
    $respuesta->excluidos = $excluidos;
    $respuesta->esbot = $esbot;
    $respuesta->esbotanalizado = $esbotanalizado;
    
return $respuesta;
}
function trending($cb, $woeid)
{
    // cachear. cada consulta consume recursos!
    $parameters = array(
        'id'=> $woeid
    );    
    
        $result = $cb->trends_place($parameters);
        
    return $result;
    
}
