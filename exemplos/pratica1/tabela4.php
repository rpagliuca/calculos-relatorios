<?php
echo "<table>";
foreach ($novas_medicoes as $id_lancamento => $lancamento) {
    $novo_lancamento = true;
    $qtd_esferas = sizeof($lancamento);
    $qtd_coordenadas = sizeof($esfera);
    $qtd_total = $qtd_coordenadas * $qtd_esferas;
    if ($novo_lancamento) {
        echo "<tr><td>$id_lancamento</td>";
        $novo_lancamento = false;
    } 

    $medida = new MedidaComposta();
    $medida->setMedidas(array(
                            'gravidade' => $gravidade,
                            'altura' => $altura[$id_lancamento],
                            'massa' => $massa
                        ))
           ->setExpression('5/7*$massa$*$gravidade$*$altura$');

    echo "<td> {$medida->getFullSI()}</td>";
    echo "</tr>";
}
echo "</table>";
?>
