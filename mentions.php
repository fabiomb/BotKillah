<?php
include_once('config.php');
include_once('db/db.php');
// Stats de palabras más usadas, números y estadísticas generales


// envío encabezados de charset
header ('Content-Type: text/html; charset=UTF-8');

// traigo el texto con menciones
$consulta = "SELECT texto FROM `tuit` WHERE texto REGEXP '@[[:alnum:]]' ";

$resultado = $db->sql_query($consulta);    
$xx = 0;
        while ($row = $db->sql_fetchrow($resultado)) 
	{
            $xx++;
            $string = $row[texto];
            //echo $string;
                preg_match_all('/(^|[^a-z0-9_])@([a-z0-9_]+)/i',$string,$m);

		$nombres = $nombres . $m[0][0]."<br />";
        }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
  
        <title></title>

    </head>
    <body>

        
        <h2>Mencionados</h2>

        <?php echo $nombres;?>
        <br />
        Total: <?php echo $xx;?>

    </body>
</html>

