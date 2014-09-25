<?php
echo "<table>";
foreach ($medicoes as $id_lancamento => $lancamento) {
    $novo_lancamento = true;
    $qtd_esferas = sizeof($lancamento);
    foreach ($lancamento as $id_esfera => $esfera) {
        $qtd_coordenadas = sizeof($esfera);
        $qtd_total = $qtd_coordenadas * $qtd_esferas;
        $nova_esfera = true;
        foreach ($esfera as $id_coordenada => $coordenada) {
            if ($novo_lancamento) {
                echo "<tr><td rowspan='$qtd_total'>$id_lancamento</td>";
                $novo_lancamento = false;
            }
            if ($nova_esfera) {
                echo "<td rowspan='$qtd_coordenadas'>$id_esfera</td>";
                $nova_esfera = false;
            }

            echo "<td>$id_coordenada</td>";
            $ponto = new MedidaPorDesvioPadrao();
            $ponto->setValores($coordenada)
                  ->setOrdem(-2);

            echo "<td>{$ponto->getFullSI()}</td>";
            $novas_medicoes[$id_lancamento][$id_esfera][$id_coordenada] = $ponto;
            echo "</tr>";
        }
    }
}
echo "</table>";
?>
