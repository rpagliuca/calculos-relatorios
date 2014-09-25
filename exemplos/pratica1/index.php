<?php
header("Content-Type: text/html; charset=UTF-8"); ?>
<html>
<head>
<style>
body {
    font-family: Verdana, sans-serif;
    font-size:90%;
}
table {
    border-collapse: collapse;
}
table tr td {
    border: 1px solid black;
    padding: 5px;
}
h3 {
    margin-top: 40px;
    font-weight: bold;
    color: black;
    font-size: 100%;
}
</style>
</head>
<body>
<?php

require_once('../classes/Cronometro.class.php');
$cronTotal = new Cronometro();
$cronTotal->iniciar();

require_once('../classes/Medida.class.php');
require_once('../classes/MedidaPorDesvioPadrao.class.php');
require_once('../classes/MedidaComposta.class.php');
include('valores.php');

$pm = '&#177;';

$cronometro = new Cronometro();
$cronometro->iniciar();

echo "<h3>Ponto médio de impacto de cada esfera para cada L:</h3>";
include('tabela1.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";
$cronometro->iniciar();

echo "<h3>Total de momentum linear antes da colisão em cada eixo para cada L:</h3>";
include('tabela2.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";
$cronometro->iniciar();

echo "<h3>Total de momentum linear depois da colisão em cada eixo para cada L:</h3>";
include('tabela3.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";
$cronometro->iniciar();

echo "<h3>Total de energia cinética de translação sem colisão para cada L (valor mais próximo da realidade):</h3>";
include('tabela6.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";
$cronometro->iniciar();

echo "<h3>Total de energia cinética de translação antes da colisão para cada L (de acordo com modelo):</h3>";
include('tabela4.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";
$cronometro->iniciar();

echo "<h3>Total de energia cinética de translação depois da colisão para cada L:</h3>";
include('tabela5.php');

$tempo = $cronometro->parar();
echo "Tempo de execução: $tempo";

echo "<br><br>Tempo total de execução: {$cronTotal->parar()}";
?>
</body>
</html>

