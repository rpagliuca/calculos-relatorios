<?php header("Content-Type: text/html; charset=UTF-8"); ?>

<html><head><link rel="stylesheet" type="text/css" href="style.css"/></head>
<body>

<?php

require_once('../classes/Cronometro.class.php');
$cronTotal = new Cronometro();
$cronTotal->iniciar();

require_once('../classes/Medidas.class.php');
include('valores.php');
$pm = '&#177;';

echo "<br><br>Tempo total de execução: {$cronTotal->parar()}";
?>
</body>
</html>

