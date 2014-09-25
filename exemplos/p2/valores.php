<?php 

$mg = new Medidas();

$ib = array('p' => 0.015, 'd' => 0.2); // Incerteza da balança
$it = array('p' => 0.015, 'd' => 0.2); // Incerteza do termopar
$im = array('p' => 0.03, 'd' => 0.2); // Incerteza do multímetro
$ia = array('p' => 0.03, 'd' => 0.002); // Incerteza do amperímetro

$mg->addMedida('cAgua')->setTexName('c_{H_{2}O}')->setValor(4186); // Calor específico da água

$mg->addMedida('mBequer1')->setValor(256.0)->setOrdem(-3)
  ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$mg->addMedida('mCalorimetro')->setValor(795.7)->setOrdem(-3)
  ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$mg->addMedida('mPeca')->setTexName('m_{p1}')->setValor(661.2)->setOrdem(-3)
   ->formatarIncertezaPercentual($it['p'], $it['d']);

// Discreto - Determinação K

$m1 = clone $mg;

$m1->addMedida('mAguaFriaComBequer1')->setTexName('m{f}')->setValor(656.5)->setOrdem(-3)
  ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$m1->addMedidaComposta('mAguaFria')->setTexName('m_{q}')
  ->setExpression('$mAguaFriaComBequer1$-$mBequer1$');

$m1->addMedida('mAguaQuenteComCalorimetro')->setTexName('m{f}')->setValor(1199.5)->setOrdem(-3)
  ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$m1->addMedidaComposta('mAguaQuente')->setTexName('m_{q}')
  ->setExpression('$mAguaQuenteComCalorimetro$-$mCalorimetro$');

$m1->addMedida('tiSistema')->setTexName('t_{mf_{i}}')->setValor(25.3)
  ->formatarIncertezaPercentual($it['p'], $it['d']);

$m1->addMedida('tiAguaQuente')->setTexName('t_{mq_{i}}')->setValor(92.0)
  ->formatarIncertezaPercentual($it['p'], $it['d']);

$m1->addMedida('tfSistema')->setTexName('t_{f}')->setValor(53.5)
   ->formatarIncertezaPercentual($it['p'], $it['d']);

// k -> Capacidade térmica do calorímetro

$m1->addMedidaComposta('k')
  ->setExpression('($mAguaQuente$*$cAgua$*($tfSistema$-$tiAguaQuente$)+$mAguaFria$*$cAgua$*($tfSistema$-$tiSistema$))/-($tfSistema$-$tiSistema$)');

// Discreto - Peça 1

$m2 = clone $mg;

$m2->addObjetoMedida($m1->getMedida('k'));

$m2->addMedida('mAguaFriaComBequer1')->setTexName('m{f}')->setValor(709.4)->setOrdem(-3)
   ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$m2->addMedidaComposta('mAguaFria')->setTexName('m_{q}')
   ->setExpression('$mAguaFriaComBequer1$-$mBequer1$');

$m2->addMedida('tiSistema')->setTexName('t_{mf_{i}}')->setValor(24.9)
   ->formatarIncertezaPercentual($it['p'], $it['d']);

$m2->addMedida('mAguaQuenteComCalorimetro')->setValor(1420.7)->setOrdem(-3)
   ->formatarIncertezaPercentual($ib['p'], $ib['d']);

$m2->addMedidaComposta('mAguaQuente')->setTexName('m_{q}')
   ->setExpression('$mAguaQuenteComCalorimetro$-$mCalorimetro$');

$m2->addMedida('tiAguaQuente')->setTexName('t_{mq_{i}}')->setValor(89.0)
  ->formatarIncertezaPercentual($it['p'], $it['d']);

$m2->addMedida('tfSistema')->setTexName('t_{f}')->setValor(58.4)
   ->formatarIncertezaPercentual($it['p'], $it['d']);

// c1 -> Calor específico da peça 1 

$m2->addMedidaComposta('c')
   ->setExpression('-($mAguaQuente$*$cAgua$*($tfSistema$-$tiAguaQuente$)+$mAguaFria$*$cAgua$*($tfSistema$-$tiSistema$)+$k$*($tfSistema$-$tiSistema$))/($mPeca$*($tfSistema$-$tiSistema$))');
// 
// // Discreto - Peça 2
// 
// $m3 = clone $mg;
// 
// $m3->addObjetoMedida($m1->getMedida('k'));
// 
// $m3->addMedida('massaFria')->setTexName('m{f}')->setValor(241.9)->setOrdem(-3)
//   ->formatarIncertezaPercentual($ib['p'], $ib['d']);
// 
// $m3->addMedida('tempInicialMassaFria')->setTexName('t_{mf_{i}}')->setValor(28.7)
//   ->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m3->addMedida('massaPeca1')->setTexName('m_{p1}')->setValor(41.8)->setOrdem(-3)
//   ->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m3->addMedida('massaQuenteMaisBequer')->setValor(663.1)->setOrdem(-3)
//   ->formatarIncertezaPercentual($ib['p'], $ib['d']);
// 
// $m3->addMedidaComposta('massaQuente')->setTexName('m_{q}')
//   ->setExpression('$massaQuenteMaisBequer$-$bequerGrande2$');
// 
// $m3->addMedida('tempInicialMassaQuente')->setTexName('t_{mq_{i}}')->setValor(64.3)
//   ->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m3->addMedida('tempFinalSistema')->setTexName('t_{f}')->setValor(47.4)
//    ->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// // c1 -> Calor específico da peça 2
// 
// $m3->addMedidaComposta('c2')
//   ->setExpression('-($massaQuente$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaQuente$)+$massaFria$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaFria$)+$k$*($tempFinalSistema$-$tempInicialMassaFria$))/($tempFinalSistema$-$tempInicialMassaFria$)');
// 
// // Discreto - Relatório Outro  em SI
// 
// $m4 = new Medidas();
// 
// $m4->addObjetoMedida($mg->getMedida('calorEspecificoAgua'));
// 
// $m4->addMedida('massaFria')->setTexName('m{f}')->setValor(400)->setIncerteza(0.1)->setOrdem(-3);
// 
// $m4->addMedida('massaQuente')->setValor(100)->setIncerteza(0.1)->setOrdem(-3);
// 
// $m4->addMedida('tempInicialMassaFria')->setTexName('t_{mf_{i}}')->setValor(21)->setIncerteza(1);
// 
// $m4->addMedida('tempInicialMassaQuente')->setTexName('t_{mq_{i}}')->setValor(90)->setIncerteza(1);
// 
// $m4->addMedida('tempFinalSistema')->setTexName('t_{f}')->setValor(32)->setIncerteza(1);
// 
// $m4->addMedidaComposta('kdeles')
//   ->setExpression('-($massaQuente$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaQuente$)+$massaFria$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaFria$))/($tempFinalSistema$-$tempInicialMassaFria$)');
// 
// // Discreto - Relatório Outro em unidades diferentes
// 
// $m5 = new Medidas(); 
// 
// $m5->addMedida('calorEspecificoAgua')->setValor(1);
// 
// $m5->addMedida('massaFria')->setTexName('m{f}')->setValor(400)->setIncerteza(0.1);
// 
// $m5->addMedida('massaQuente')->setValor(100)->setIncerteza(0.1);
// 
// $m5->addMedida('tempInicialMassaFria')->setTexName('t_{mf_{i}}')->setValor(21)->setIncerteza(1);
// 
// $m5->addMedida('tempInicialMassaQuente')->setTexName('t_{mq_{i}}')->setValor(90)->setIncerteza(1);
// 
// $m5->addMedida('tempFinalSistema')->setTexName('t_{f}')->setValor(32)->setIncerteza(1);
// 
// // c1 -> Calor específico da peça 2
// 
// $m5->addMedidaComposta('kdeles')
//   ->setExpression('-($massaQuente$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaQuente$)+$massaFria$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaFria$))/($tempFinalSistema$-$tempInicialMassaFria$)');
// 
// // Discreto - Relatório Outro  em SI com nossa incerteza
// 
// $m6 = new Medidas();
// 
// $m6->addObjetoMedida($mg->getMedida('calorEspecificoAgua'));
// 
// $m6->addMedida('massaFria')->setTexName('m{f}')->setValor(400)->formatarIncertezaPercentual($ib['p'], $ib['d'])->setOrdem(-3);
// 
// $m6->addMedida('massaQuente')->setValor(100)->formatarIncertezaPercentual($ib['p'], $ib['d'])->setOrdem(-3);
// 
// $m6->addMedida('tempInicialMassaFria')->setTexName('t_{mf_{i}}')->setValor(21)->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m6->addMedida('tempInicialMassaQuente')->setTexName('t_{mq_{i}}')->setValor(90)->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m6->addMedida('tempFinalSistema')->setTexName('t_{f}')->setValor(32)->formatarIncertezaPercentual($it['p'], $it['d']);
// 
// $m6->addMedidaComposta('kdeles')
//   ->setExpression('-($massaQuente$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaQuente$)+$massaFria$*$calorEspecificoAgua$*($tempFinalSistema$-$tempInicialMassaFria$))/($tempFinalSistema$-$tempInicialMassaFria$)');

$m1->debugAll();
echo "<hr>";
$m1->getMedida('k')->debugComposicaoIncerteza();
echo "<hr>";
$m2->debugAll();
echo "<hr>";
$m2->getMedida('c')->debugComposicaoIncerteza();
// echo "<hr>";
// $m3->debugAll();
// echo "<hr>";
// $m3->getMedida('c2')->debugComposicaoIncerteza();
// echo "<hr>";
// $m4->debugAll();
// echo "<hr>";
// $m4->getMedida('kdeles')->debugComposicaoIncerteza();
// echo "<hr>";
// $m5->debugAll();
// echo "<hr>";
// $m5->getMedida('kdeles')->debugComposicaoIncerteza();
// echo "<hr>";
// $m6->debugAll();
// echo "<hr>";
// $m6->getMedida('kdeles')->debugComposicaoIncerteza();

?>
