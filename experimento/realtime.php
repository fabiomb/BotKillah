<?php
// BÚSQUEDA POR HASHTAG FORZADO
// trabajo en curso, falta mucho:
// insertar hashtag
// relanzar la búsqueda
// guardar tuit y cómo usó el ht
// armado de la red si es que existe
// crear una nueva tabla donde se guarden los usuarios-tuits de un HT específico

session_start();


include_once('config.php');
include_once('db/db.php');

date_default_timezone_set('UTC');

// Conecto con Twitter
        require_once ('codebird-php.php');
        \Codebird\Codebird::setConsumerKey($tw_consumer, $tw_secret); // static, see 'Using multiple Codebird instances'

        $cb = \Codebird\Codebird::getInstance();
        
        $cb->setToken($tw_token_a, $tw_token_b);


$cuenta = 0;        
$nextCursor = "-1";        

//Por hashtag
$hashtagtrucho = "#GrieFault";


echo "Buscando clones usando el hashtag $hashtagtrucho<br /><br />";
                $parameters = array(
                    'cursor' => $nextCursor,
                    'count' =>100,
                    'q'=> $hashtagtrucho                );

    $results[$cuenta] = $cb->search_tweets($parameters);
    if ($results[$cuenta]->errors)
    {   // en caso de error por llegar al límite
        echo "Error! ".$results[$cuenta]->errors[0]->message."<br />";
    }
    
    echo "======================================================= Siguiente<br />";
    $nextCursor = $results[$cuenta]->search_metadata->max_id_str;
    
        $statuses = $results[$cuenta]->statuses;
        
        echo "estatuses:".count($statuses)."<br />";     
    
   // var_dump($results[$cuenta]);        
    //recorro el cursor para buscar el siguiente
    while ($nextCursor) {  
        $old_cursor = $nextCursor;
        //print_r($results[$cuenta]);
        $nextCursor = $results[$cuenta]->search_metadata->max_id_str;
        
        if (($nextCursor == $old_cursor) and ($cuenta > 2))
        {
            echo "Se repite!";
            $nextCursor = NULL; // se repite
        }
        
        echo "Cuenta: $cuenta Cursor anterior: $old_cursor Cursor nuevo: $nextCursor.<br />";
        if ($nextCursor > 0) {
            echo "Nuevo cursor encontrado! $nextCursor<br />";
            $cuenta++;
            // 100 es el máximo que devuelve la API de Twitter en búsqueda
                $parameters = array(
                    'since_id' => $nextCursor,
                    'count' =>100,
                    'q'=> $hashtagtrucho
                );
            $results[$cuenta] = $cb->search_tweets($parameters);
            
            //var_dump($results[$cuenta]);
            $sig = $results[$cuenta]->search_metadata->max_id_str;
            echo "Siguiente: ".$sig."<br /><br />";
        }
        $statuses = $results[$cuenta]->statuses;
        
        echo "estatuses:".count($statuses)."<br />"; 
        if (($nextCursor == "0") or  ($nextCursor == "-1") or ($statuses =="0")) {$nextCursor = NULL;} // vacío
        if ($cuenta == 20) {$nextCursor = NULL;} // protección
    }

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        
        foreach ($results as $resulta)
        {
        echo "###=======================================================<br />";
        $x = 0;
        foreach ($resulta as $arrayfollowers)
        {
            
            $x++;
            if ($x == 1)
            {
                foreach ($arrayfollowers as $followers)
                {
                echo  $followers->id_str."<br />";    
                echo  $followers->user->screen_name."<br />";    
                echo  $followers->user->followers_count."<br />";    
                echo  $followers->user->friends_count."<br />";    
                
                
                //var_dump($followers);
                $name = $followers->user->name;
                $screen_name = $followers->user->screen_name;
                $location = $followers->user->location;
                $description = $followers->user->description;
                $followers_count = $followers->user->followers_count;
                $friends_count = $followers->user->friends_count;
                $created_at = $followers->user->created_at;
                $statuses_count = $followers->user->statuses_count;
                $lang = $followers->user->lang;
               
                
                echo "=======================================================<br />";
                $guarda = "INSERT INTO usuario (id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang) "
                        . "VALUES ('$followers->id_str','$name', '$screen_name', '$location',"
                        . " '$description', "
                        . "'$followers_count', '$friends_count', '$created_at',"
                        . " '$statuses_count', '$lang' )";
                $db->sql_query($guarda);
                
                //$relacion = "INSERT INTO relacion (id_str_inicio, id_str_destino) VALUES ('$id_str','$followers->id_str')";
                
                //$db->sql_query($relacion);
                
                }
            }
            else
            {
                // ?
            }
            // lo marco como visto para no volver a usarlo
          $actualizado = "UPDATE usuario SET visto = 1 WHERE id_str = '$id_str'";  
          $db->sql_query($actualizado);
        }
        }
        ?>
        
        

        
    </body>
</html>
