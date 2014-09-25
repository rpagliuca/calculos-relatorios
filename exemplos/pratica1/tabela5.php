<?php
echo "<table>";
foreach ($novas_medicoes as $id_lancamento => $lancamento) {
    $i = 0;
    $posicoes = array();
    echo "<tr><td>$id_lancamento</td>";
    foreach ($lancamento as $id_esfera => $esfera) {
        foreach ($esfera as $id_coordenada => $coordenada) {
            $i++;
            $posicoes[$i] = $esfera[$id_coordenada];
        }
    }
    foreach ($posicoes as $posicao) {
        $energiaCinetica = new MedidaComposta();
        $expressao = '1/2*$massa$*(0';
        foreach ($posicoes as $id => $posicao) {
            $energiaCinetica->addMedida(array("posicao_$id" => $posicao));
            $expressao .= '+ ($posicao_' . $id . '$/$tf$)*($posicao_' . $id . '$/$tf$) ';
        }
        $expressao .= ')';
        $energiaCinetica->addMedida(array('massa' => $massa, 'tf' => $tf))
                        ->setExpression($expressao);
    }
    echo "<td>{$energiaCinetica->getFullSI()}</td>";
    echo "</tr>";
}
echo "</table>";
