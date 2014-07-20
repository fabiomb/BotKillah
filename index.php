<?php
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

//Primer seed utilizado, id y username
//$id_str = "2583563579";
//$screen_name = "zenna159";

if (isset($_GET["id_str"])) {$id_str = $_GET["id_str"];}
if (isset($_GET["screen_name"])) {$screen_name = $_GET["screen_name"];}

if ($screen_name == "")
{
    // entrada por default, comienza el spider buscando uno que esté disponible
        $siguiente = "SELECT * FROM `usuario` WHERE visto = 0 and esbot =1  and excluir = 0 limit 0,1";  
        $resultadosig = $db->sql_query($siguiente);        
        while ($row = $db->sql_fetchrow($resultadosig)) 
	{
		 $id_str = $row[id_str]; 
                 $screen_name = $row[screen_name]; 
        }
}

echo "Buscando clones en $screen_name<br /><br />";
                $parameters = array(
                    'cursor' => $nextCursor,
                    'count' =>200,
                    'screen_name'=> $screen_name
                );

    $results[$cuenta] = $cb->followers_list($parameters);
    if ($results[$cuenta]->errors)
    {   // en caso de error por llegar al límite
        echo "Error! ".$results[$cuenta]->errors[0]->message."<br />";
    }
        
    //recorro el cursor para buscar el siguiente
    while ($nextCursor) {  
        $old_cursor = $nextCursor;
        $nextCursor = $results[$cuenta]->next_cursor_str;
        
        if (($nextCursor == $old_cursor) and ($cuenta > 2))
        {
            echo "Se repite!";
            $nextCursor = NULL; // se repite
        }
        
        echo "Cuenta: $cuenta Cursor anterior: $old_cursor Cursor nuevo: $nextCursor.<br />";
        if ($nextCursor > 0) {
            echo "Nuevo cursor encontrado! $nextCursor<br />";
            $cuenta++;
            // 200 es el máximo que devuelve la API de Twitter
                $parameters = array(
                    'cursor' => $nextCursor,
                    'count' =>200,
                    'screen_name'=> $screen_name
                );
            $results[$cuenta] = $cb->followers_list($parameters);
            
            //var_dump($results[$cuenta]);
            echo "Siguiente: ".$results[$cuenta]->next_cursor_str."<br /><br />";
        }
        if (($nextCursor == "0") or  ($nextCursor == "-1")) {$nextCursor = NULL;} // vacío
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
        echo "=======================================================<br />";
        $x = 0;
        foreach ($resulta as $arrayfollowers)
        {
            
            $x++;
            if ($x == 1)
            {
                foreach ($arrayfollowers as $followers)
                {
                echo  $followers->screen_name."<br />";    
                
                $guarda = "INSERT INTO usuario (id_str, name, screen_name, location, description, followers_count, friends_count, created_at, statuses_count, lang) "
                        . "VALUES ('$followers->id_str','$followers->name', '$followers->screen_name', '$followers->location', '$followers->description', "
                        . "'$followers->followers_count', '$followers->friends_count', '$followers->created_at', '$followers->statuses_count', '$followers->lang' )";
                $db->sql_query($guarda);
                
                $relacion = "INSERT INTO relacion (id_str_inicio, id_str_destino) VALUES ('$id_str','$followers->id_str')";
                
                $db->sql_query($relacion);
                
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
        
        
        
        // si todo está ok botón con el siguiente
        
        // mismo query que si entraba vacío, utilizo un botón pero valdría automatizar
        $siguiente = "SELECT * FROM `usuario` WHERE visto = 0 and esbot =1  and excluir = 0 limit 0,1";  
        $resultadosig = $db->sql_query($siguiente);        
        while ($row = $db->sql_fetchrow($resultadosig)) 
	{
		 $nextid = $row[id_str]; 
                 $nextus = $row[screen_name]; 
        }

        ?>
        <form action="index.php" method="GET">
            
            <input type="hidden" name="id_str" value="<?php echo $nextid;?>">
            <input type="hidden" name="screen_name" value="<?php echo $nextus;?>">
            
            <input type="submit" value="Siguiente: <?php echo $nextus;?>">
        
    </body>
</html>
