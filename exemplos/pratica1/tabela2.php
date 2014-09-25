<?php
echo "<table>";
foreach ($novas_medicoes as $id_lancamento => $lancamento) {
    $novo_lancamento = true;
    $qtd_esferas = sizeof($lancamento);
    foreach (array ('X', 'Y') as $coordenada) {
        $qtd_coordenadas = sizeof($esfera);
        $qtd_total = $qtd_coordenadas * $qtd_esferas;
        if ($novo_lancamento) {
            echo "<td rowspan='3'>$id_lancamento</td>";
            $novo_lancamento = false;
        } 
        echo "<tr>";
        echo "<td>$coordenada</td>";
        $medida = new Medida();
        if ($coordenada == 'X') {
            $medida->setValor(0);
        } else {

            $medida = new MedidaComposta();
            $medida->setMedidas(array(
                                        'gravidade' => $gravidade,
                                        'altura' => $altura[$id_lancamento],
                                        'massa' => $massa
                                    ))
                   ->setExpression('sqrt(10/7*$gravidade$*$altura$)*($massa$)');
        }
        echo "<td> {$medida->getFullSI()}</td>";
        echo "</tr>";
    }
}
echo "</table>";
?>
