<?php

require_once('../classes/Medidas.class.php');

$m = new Medidas();

$m->addMedida('alfa')->setValor(1);

echo $m->getMedida('alfa')->getName();

?>
