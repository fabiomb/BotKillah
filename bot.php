<?php


function get_random_bot() {
    // entrada por default, comienza el spider buscando uno que esté disponible
    $siguiente = "SELECT * FROM `usuario` WHERE visto = 0 and esbot =1  and excluir = 0 limit 0,1";  
    $resultadosig = $db->sql_query($siguiente);        
    return $db->sql_fetchrow($resultadosig);
}

function get_followers ( $screen_name ) {
    global $cb;

    $nextCursor = "-1";        

    $parameters = array(
        'cursor' => $nextCursor,
        'count' =>200,
        'screen_name'=> $screen_name
    );

    $followers = array();

    while ( $nextCursor ) {
        $result = $cb->followers_list($parameters);
        $old_cursor = $nextCursor;
        $nextCursor = $result->next_cursor_str;

        if ($result->errors) {
            echo "Error! ".$results[$cuenta]->errors[0]->message."<br />";
        }
        
        if (($nextCursor == $old_cursor) and ($cuenta > 2)) {
            echo "Se repite!";
            $nextCursor = NULL; // se repite
        }

        if (($nextCursor == "0") or  ($nextCursor == "-1")) {$nextCursor = NULL;} // vacío

        $followers = array_merge($followers, $result->users);
    }

    return $followers;
}


