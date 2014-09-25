<?php

$antes = '(calorEspecificoAgua*$massaQuente$*($tempFinalSistema$-$tempInicialMassaQuente$)+calorEspecificoAgua*$massaFria$*($tempFinalSistema$-tempInicialMassaFria))/(tempInicialMassaFriaTeste-$tempFinalSistema$)^2+(calorEspecificoAgua*$massaQuente$+calorEspecificoAgua*$massaFria$)/(tempInicialMassaFria-$tempFinalSistema$)';

echo $antes;

echo "<hr>";

$var = 'tempInicialMassaFria';

$depois = preg_replace("~([^A-Za-z]+?|^)$var([^A-Za-z]+?|$)~", "$1\$$var\$$2", $antes);

echo $depois;

?>
