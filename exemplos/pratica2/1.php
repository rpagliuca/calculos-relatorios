<?php

    echo '<b>Medidas globais:</b>';
    echo '<br>';

    echo $m1->getMedida('bequerGrande1')->getNameAndSI();
    echo '<br>';
    
    echo $m1->getMedida('bequerGrande2')->getNameAndSI();
    echo '<br>';

    echo $m1->getMedida('bequerPequeno')->getNameAndSI();
    echo '<br>';

    echo $m1->getMedida('calorEspecificoAgua')->getNameAndSI();
    echo '<br>';


    echo '<br>';
    echo '<b>Obtenção do K:</b>';
    echo '<br>';

    echo $m1->getMedida('massaQuente')->getNameAndSI();
    echo "<br>";

    echo $m1->getMedida('massaFria')->getNameAndSI();
    echo "<br>";

    echo $m1->getMedida('massaQuenteMaisBequer')->getNameAndSI();
    echo "<br>";

    echo $m1->getMedida('k')->getNameAndSI();
    echo "<br>";


    echo '<br>';
    echo '<b>Obtenção de C1 (peça 1):</b>';
    echo '<br>';

    echo $m1->getMedida('c1')->getNameAndSI();
    echo "<br>";


    echo '<br>';
    echo '<b>Obtenção de C2 (peça 2):</b>';
    echo '<br>';

    echo $m1->getMedida('c2')->getNameAndSI();
    echo "<br>";

    //$m->getMedida('k')->debugComposicaoIncerteza();
    //$m->getMedida('k')->debugTex();
?>
