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

        $followers = array_merge($followers, $result->users);

        // Asi no bardea el limite
        if ( $i > 5 ) { break; }
    }

    return $followers;
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

        $followers = array_merge($followers, $result->users);

        // Asi no bardea el limite
        if ( $i > 5 ) { break; }
    }

    return $followers;
}

function handle_errors($result) {
    if ($result->errors) {
        echo '<div class="alert alert-warning"><strong>Error!</strong> '.$result->errors[0]->message."</div><br />";
    }
}
