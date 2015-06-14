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
$menciones = array();
        while ($row = $db->sql_fetchrow($resultado)) 
	{
            $xx++;
            $string = $row[texto];
            //echo $string;
                preg_match_all('/(^|[^a-z0-9_])@([a-z0-9_]+)/i',$string,$m);
                $menciones[] = $m[0][0];
		$nombres = $nombres . $m[0][0]."<br />";
        }
$menciones = array_unique($menciones,SORT_REGULAR);
$cuenta = count ($menciones);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
  
        <title></title>

    </head>
    <body>

        
        <h2>Mencionados</h2>

        <?php foreach ($menciones as $mencion) {
            echo $mencion."<br />";}?>
        <br />
        Total: <?php echo $cuenta;?>

    </body>
</html>

