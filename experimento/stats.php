<?php
include_once('config.php');
include_once('db/db.php');
// Stats de palabras más usadas, números y estadísticas generales


// envío encabezados de charset
header ('Content-Type: text/html; charset=UTF-8');


$consulta = "SELECT  name FROM `usuario` WHERE esbot = 1";



$resultado = $db->sql_query($consulta);    

//$total =  $db->sql_numrows($resultado);

//$cual = 0;
        while ($row = $db->sql_fetchrow($resultado)) 
	{
      
		$name = $name . $row[name]. " "; 
        }
        $word_use =  array_count_values(str_word_count($name, 1, 'áéíóúñüëäÁÉÓÚÍËÜ.'));
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
  
        <title></title>

    </head>
    <body>

        
        <h2>Uso de palabras</h2>
        <pre>
        <?php echo print_r($word_use);?>
</pre>
    </body>
</html>

