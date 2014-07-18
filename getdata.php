<?php
include_once('config.php');
include_once('db/db.php');

header('Content-Type: application/json');
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
,
(SELECT count(*) FROM relacion C WHERE C.id_str_destino = A.id_str) cuentaC


FROM usuario A
WHERE (

SELECT count( * )
FROM relacion B
WHERE B.id_str_inicio = A.id_str
) > 100
and

(SELECT count(*) FROM relacion C WHERE C.id_str_destino = A.id_str) > 10
LIMIT 0 , 500";// los primeros 50
$resultado = $db->sql_query($consulta);    

$total =  $db->sql_numrows($resultado);

$cual = 0;
        while ($row = $db->sql_fetchrow($resultado)) 
	{
            $cual++;
		$id_str = $row[id_str]; 
                $screen_name = $row[screen_name]; 
                $cuenta = $row[cuenta];
                if ($cuenta == 0) {$cuenta = 1;}
                
                $x = rand (0 , 1000 );
                $y = rand (0 , 600 );
                $radio = 100;
                $angulo = deg2rad((360/$total)*$cual);
                
                $objeto = array();
                $objeto[id]=$id_str;
                $objeto[label]=$screen_name;
                $objeto[x]= $radio + ($radio * cos($angulo));
                $objeto[y]= $radio + ($radio * sin($angulo));
                $objeto[size]=$cuenta;
                
                $objeto[color]= "#".random_color();
                
                array_push($wherein, $id_str);

                array_push ($datos[nodes], $objeto);
                
                
                unset($objeto);
        }        
       
//        (object) array(id => '', label=>'', x=>'', y=>'', size=>'3');

// selecciono las relaciones de esos nodos
        
        $wherein = implode (",",$wherein);

          $siguiente = "SELECT id_str_inicio, id_str_destino FROM `relacion` WHERE id_str_inicio in ($wherein) and id_str_destino in ($wherein)";  
          //echo $siguiente;
          $resultadosig = $db->sql_query($siguiente);        
          $x = 0 ;
        while ($row = $db->sql_fetchrow($resultadosig)) 
	{
		$id_str_inicio = $row[id_str_inicio]; 
                $id_str_destino = $row[id_str_destino]; 
                $x++;

                $relacion = array();
                $relacion[id] = "REL".$x;
                $relacion[source] = $id_str_inicio;
                $relacion[target] = $id_str_destino;
                
                array_push ($datos[edges], $relacion);
                
                unset($relacion);
        }
        //print_r($datos);
        
        
        echo json_encode ($datos);
        
        //echo json_last_error_msg();
/*
{
  "nodes": [
    {
      "id": "n0",
      "label": "A node",
      "x": 0,
      "y": 0,
      "size": 3
    },
    {
      "id": "n1",
      "label": "Another node",
      "x": 3,
      "y": 1,
      "size": 2
    },
    {
      "id": "n2",
      "label": "And a last one",
      "x": 1,
      "y": 3,
      "size": 1
    }
  ],
  "edges": [
    {
      "id": "e0",
      "source": "n0",
      "target": "n1"
    },
    {
      "id": "e1",
      "source": "n1",
      "target": "n2"
    },
    {
      "id": "e2",
      "source": "n2",
      "target": "n0"
    }
  ]
}
*/
        
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}