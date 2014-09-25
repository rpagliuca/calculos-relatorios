<?php
echo "<table>";
foreach ($novas_medicoes as $id_lancamento => $lancamento) {
    $novo_lancamento = true;
    $qtd_esferas = sizeof($lancamento);
    foreach (array ('X', 'Y') as $coordenada) {
        $i = 1;
        $nova_coordenada = true;
        foreach ($lancamento as $id_esfera => $esfera) {
            $qtd_coordenadas = sizeof($esfera);
            $qtd_total = $qtd_coordenadas * $qtd_esferas;
            $nova_esfera = true;
            if ($novo_lancamento) {
                echo "<td rowspan='3'>$id_lancamento</td>";
                $novo_lancamento = false;
                $momenta = array();
            } 
            $momentum = new MedidaComposta();
            $momentum->setMedidas(array('massa' => $massa, 'coordenada' => $esfera[$coordenada], 'tf' => $tf))
                     ->setExpression('$massa$*$coordenada$/$tf$');
            $momenta[$i] = $momentum;
            $i++;
            if ($nova_coordenada) {
                echo "<tr>";
                echo "<td>$coordenada</td>";
                $nova_coordenada = false;
            } else { 
                $somaMomenta = new MedidaComposta();
                $expressao = '0 ';
                foreach ($momenta as $id => $momentum) {
                    $somaMomenta->addMedida(array("momentum_$id" => $momentum));
                    $expressao .= ' + $momentum_' . $id . '$ ';
                }
                $somaMomenta->setExpression($expressao);
                echo "<td>{$somaMomenta->getFullSI()}</td>";
                echo "</tr>";
            }
        }
    }
}
echo "</table>";
