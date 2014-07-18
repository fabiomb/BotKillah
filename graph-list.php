<?php
include_once('config.php');
include_once('db/db.php');

//header('Content-Type: application/json');
// creo los nodos iniciales

$datos = array();
$datos["nodes"] = array();
$datos["edges"] = array(); 

$wherein = array();
// selecciono los nodos
$consulta = "SELECT A.id_str, A.screen_name, (

SELECT count( * )
FROM relacion B
WHERE B.id_str_inicio = A.id_str
) cuenta
FROM usuario A
WHERE (

SELECT count( * )
FROM relacion B
WHERE B.id_str_inicio = A.id_str
) > 50
LIMIT 0 , 50";// los primeros 50
$resultado = $db->sql_query($consulta);    


        while ($row = $db->sql_fetchrow($resultado)) 
	{
                // inicializo un vector para recorrer y una diagonal en cero para crear la matriz
                
                
		$id_str = $row[id_str]; 
                $screen_name = $row[screen_name];                 
                $matrix[$id_str][$id_str] = 0;
                $lineal[$id_str] = $id_str;
                $traductor[$id_str] = $screen_name;
                $cuenta = $row[cuenta];
        }        
        echo "<table border ='1'>";
            echo "<tr>";
            echo "<td></td>";

        foreach ($lineal as $ejex)
            {
                echo "<td>$traductor[$ejex]</td>";
            }
            echo "</tr>\n";
            
        foreach ($lineal as $ejex)
        {
            echo "<tr>";
            echo "<td>$traductor[$ejex]</td>";
            foreach ($lineal as $ejey)
            {
                //echo "$ejex , $ejey<br />";
                $matrix[$ejex][$ejey] = "0";
                // busco si hay relación entre ejex y ejey, marco
                $consultax = "SELECT id_str_inicio, id_str_destino FROM `relacion` WHERE id_str_inicio = '$ejex' and id_str_destino = '$ejey'";
                //echo $consultax;
                $resultadox = $db->sql_query($consultax);    
                        while ($rowx = $db->sql_fetchrow($resultadox)) 
                        {
                            //echo "Coincidencia entre $ejex , $ejey<br />";
                            $matrix[$ejex][$ejey]++;
                        }
                        unset ($consultax);
                        unset ($resultadox);
                        if ($matrix[$ejex][$ejey] == 0)
                        {
                            $expone = "&nbsp;";
                        }
                        else
                        {
                            $expone = "X";
                        }
                echo "<td>".$expone."</td>";        
            }
            echo "</tr>\n";
        }
        echo "</table>";    
         
        //print_r($matrix);
        
        // armo matriz
        
        // Por cada columna consulto coincidencias en base a esa lista de ids