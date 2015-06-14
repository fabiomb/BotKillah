<?php


function get_random_bot() {
    global $db;
    // entrada por default, comienza el spider buscando uno que esté disponible
    $siguiente = "SELECT * FROM `usuario` WHERE visto = 0 and esbot =1  and excluir = 0 limit 0,1";  
    $resultadosig = $db->sql_query($siguiente);        
    return $db->sql_fetchrow($resultadosig);
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
        if ( $i > 6 ) { break; }
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

function save_if_not_exist($f) {
    global $db;
    $guarda = "INSERT INTO usuario (id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang) "
            . "VALUES ('$f->id_str','$f->name', '$f->screen_name', '$f->location', '$f->description', "
            . "'$f->followers_count', '$f->friends_count', '$f->created_at', '$f->statuses_count', '$f->lang' )";
    $db->sql_query($guarda);
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