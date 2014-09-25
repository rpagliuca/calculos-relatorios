<?php

header('Content-type: text/html; charset=utf-8');

require_once('../classes/Medida.class.php');
require_once('../classes/MedidaComposta.class.php');

echo "<h3>Propagação de incerteza baseada no exemplo da Apostila FEX-A</h3>";

// Teste
$massa = new Medida();
$massa->setValor(145.7)
      ->setIncerteza(0.6);

$volume = new Medida();
$volume->setValor(65.34)
       ->setIncerteza(0.03);

$densidade = new MedidaComposta();
$densidade->setExpression('$massa$/$volume$')
          ->setMedidas(array('massa' => $massa, 'volume' => $volume));

echo $densidade->getFullSI();
die();

// Fim Teste

?>
