<?php
include_once('config.php');
include_once('db/db.php');
// Gráfico utilizando Sigma
// No funciona muy bien, lo mejor sería utilizar algo más decente
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
  
        <title></title>
        <script src="plugins/sigma.core.js"></script>
        <script src="plugins/conrad.js"></script>
        <script src="plugins/utils/sigma.utils.js"></script>
        <script src="plugins/utils/sigma.polyfills.js"></script>
        <script src="plugins/sigma.settings.js"></script>
        <script src="plugins/classes/sigma.classes.dispatcher.js"></script>
        <script src="plugins/classes/sigma.classes.configurable.js"></script>
        <script src="plugins/classes/sigma.classes.graph.js"></script>
        <script src="plugins/classes/sigma.classes.camera.js"></script>
        <script src="plugins/classes/sigma.classes.quad.js"></script>
        <script src="plugins/captors/sigma.captors.mouse.js"></script>
        <script src="plugins/captors/sigma.captors.touch.js"></script>
        <script src="plugins/renderers/sigma.renderers.canvas.js"></script>
        <script src="plugins/renderers/sigma.renderers.webgl.js"></script>
        <script src="plugins/renderers/sigma.renderers.def.js"></script>
        <script src="plugins/renderers/webgl/sigma.webgl.nodes.def.js"></script>
        <script src="plugins/renderers/webgl/sigma.webgl.nodes.fast.js"></script>
        <script src="plugins/renderers/webgl/sigma.webgl.edges.def.js"></script>
        <script src="plugins/renderers/webgl/sigma.webgl.edges.fast.js"></script>
        <script src="plugins/renderers/webgl/sigma.webgl.edges.arrow.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.labels.def.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.hovers.def.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.nodes.def.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.edges.def.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.edges.curve.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.edges.arrow.js"></script>
        <script src="plugins/renderers/canvas/sigma.canvas.edges.curvedArrow.js"></script>
        <script src="plugins/middlewares/sigma.middlewares.rescale.js"></script>
        <script src="plugins/middlewares/sigma.middlewares.copy.js"></script>
        <script src="plugins/misc/sigma.misc.animation.js"></script>
        <script src="plugins/misc/sigma.misc.bindEvents.js"></script>
        <script src="plugins/misc/sigma.misc.drawHovers.js"></script>
        <!-- END SIGMA IMPORTS -->
        <script src="plugins/sigma.plugins.dragNodes/sigma.plugins.dragNodes.js"></script>
        <script src="plugins/sigma.parsers.json/sigma.parsers.json.js"></script>
        <style type="text/css">
          #container {
            max-width: 1250px;
            height: 1000px;
            margin: auto;
          }
              #graph-container {
            max-width: 1250px;
            height: 900px;
        border: 1px solid;;
            }
        </style>
    </head>
    <body>
        <div id="graph-container"></div>

<script>
    var s, g;

        s = new sigma({
          graph: g,
          container: 'graph-container'
        });


        sigma.parsers.json('getdata.php', s);

        sigma.renderers.def = sigma.renderers.canvas
        // Instanciate sigma:

        // Initialize the dragNodes plugin:
        sigma.plugins.dragNodes(s, s.renderers[0]);



</script>

    </body>
</html>

