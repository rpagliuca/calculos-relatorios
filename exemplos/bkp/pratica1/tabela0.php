<html>
<head>
<style>
    table {
        border: 1px solid black;
        border-collapse: collapse;
    }
    table tr td {
        border: 1px solid black;
        padding: 5px;
    }
</style>
</head>
<body>
<?php
require_once('../classes/Medida.class.php');
require_once('../classes/MedidaPorDesvioPadrao.class.php');
require_once('../classes/MedidaComposta.class.php');
require_once('../classes/html_table.class.php');
require_once('valores.php');

$tabela = new html_table();
$cells = array();

foreach ($medicoes as $id_lancamento => $lancamento) {
    foreach ($lancamento as $id_esfera => $esfera) {
        foreach ($esfera as $id_coordenada => $coordenada) {
            $ponto = new MedidaPorDesvioPadrao();
            $ponto->setValores($coordenada)
                  ->setOrdem(-2);
            $cells = array_merge($cells, array($id_lancamento, $id_esfera, $id_coordenada, $ponto->getFullSI()));
            $novas_medicoes[$id_lancamento][$id_esfera][$id_coordenada] = $ponto;
        }
    }
}

$tabela->init_fill_bf_row($cells, 6);
$tabela->display();


?>
</body>
</html>
