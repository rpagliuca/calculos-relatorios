<?php
echo "<table>";
foreach ($alcances_sem_colisao_dp as $lancamento_id => $alcance) {

    $energia = new MedidaComposta();
    $energia->setMedidas(array(
                                'massa' => $massa,
                                'alcance' => $alcance,
                                'tf' => $tf ))
            ->setExpression('1/2*$massa$*($alcance$/$tf$)*($alcance$/$tf$)');

    echo "<td>$lancamento_id</td>";
    echo "<td> {$energia->getFullSI()}</td>";
    echo "</tr>";
}
echo "</table>";
?>
