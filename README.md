BotKillah
=========

Script para detección de bots en twitter

config.php.sample => copiar a config.php e ingresar parámetros

index.php => Escanea a partir de la base de datos o de parámetros $id_str y $screen_name, el usuario de Twitter "semilla"

mentions.php => Menciones a usuarios que realizan los bots, se puede modificar para que filtre hashtags, etc.

semilla.php => Inserta semilla inicial, un bot conocido

bot.php => Librería principal

scrap.php => Ejemplo de scrapper de portada de Twitter

sql/empty.sql => Base de datos



En experimento se encuentran algunas pruebas:

getdata.php => Datos a JSON para gráficos con límites "a mano", falta modificar y hacer configurable
graph.php => Gráfico de red usando Sigma.js
graph-list.php => Grid de relaciones de los más relevantes



Bases de datos de ejemplo:
/sql/botkillah.sql.gz => Datos de ejemplo de red existente

## Dependencias

* PHP +5.x
* MySQL
* php-curl, php-mysql


## TODO

Parametrizar 

$tags = array ("", ""); // tags para coincidencias con bots

set_time_limit(1200); // aumento el tiempo de ejecución

$limite_tags = 10; // límite a partir del cual se considera coincidencia abundante

$woeid ="468739"; // 468739 = Buenos Aires http://developer.yahoo.com/geo/geoplanet/


Estos parámetros deberían configurarse en otro lado, estan en index.php por apuro