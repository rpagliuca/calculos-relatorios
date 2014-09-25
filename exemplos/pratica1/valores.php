<?php 

$massa = new Medida();
$massa->setValor(44)
      ->setOrdem(-3)
      ->formatarIncertezaPercentual(0.03, 2);

$gravidade = new Medida();
$gravidade->setValor(9.78)
          ->setIncerteza(0.2);

$z0 = new Medida();
$z0->setValor(91.4)
   ->setIncerteza(0.2)
   ->setOrdem(-2);

$tf = new MedidaComposta();
$tf->setMedidas(array('z0' => $z0, 'gravidade' => $gravidade))
   ->setExpression('sqrt(2*$z0$/$gravidade$)');

$altura['L1'] = new Medida();
$altura['L1']->setValor(4.7)
             ->setIncerteza(0.2)
             ->setOrdem(-2);

$altura['L2'] = new Medida();
$altura['L2']->setValor(11.5)
             ->setIncerteza(0.2)
             ->setOrdem(-2);

$altura['L3'] = new Medida();
$altura['L3']->setValor(20.4)
             ->setIncerteza(0.2)
             ->setOrdem(-2);

$medicoes = array(
    'L1' => array(
        'A' => array(
            'X' => '-4.41 -4.26 -3.65 -4.11 -4.04 -3.85 -1.42 -2.02 -4.04 -4.52', 
            'Y' => '17.39 16.08 16.26 17.28 16.57 16.27 17.17 16.99 16.57 16.99', 
        ),
        'B' => array(
            'X' => '3.49 5.10 3.98 3.79 4.18 3.10 3.19 2.25 2.47 4.78', 
            'Y' => '9.77 10.00 9.45 9.66 9.54 9.88 9.58 8.72 9.31 9.52', 
        ),
    ),
    'L2' => array(
        'A' => array(
            'X' => '-13.65 -14.70 -14.36 -13.78 -14.20 -12.47 -13.60 -14.10 -13.05', 
            'Y' => '34.05 29.19 28.41 30.74 31.02 32.41 29.15 29.02 32.18', 
        ),
        'B' => array(
            'X' => '11.36 15.99 15.99 13.37 14.19 11.98 15.14 17.17 12.64', 
            'Y' => '18.70 20.24 20.34 18.70 20.15 16.33 19.20 20.60 17.17', 
        ),
    ),
    'L3' => array(
        'A' => array(
            'X' => '-22.39 -22.23 -20.30 -22.89 -15.88 -22.41 -21.77 -21.21 -20.30 -21.22', 
            'Y' => '37.32 37.74 37.52 33.16 43.78 35.51 37.18 43.87 40.64 39.85', 
        ),
        'B' => array(
            'X' => '22.96 22.27 19.57 25.18 15.79 24.19 23.64 16.70 18.62 21.00', 
            'Y' => '25.52 25.36 26.30 29.55 23.73 27.35 24.68 27.83 24.60 24.93', 
        ),
    ),
);

$alcances_sem_colisao = array(
    'L1' => '31.7 32.4 32.1 32.0 31.8 32.2 32.5 32.2 32.6 32.5 33.3',
    'L2' => '53.9 54.1 54.3 54.7 54.5 54.3 54.7',
    'L3' => '69.6 69.3 69.4 69.2 69.4 69.6 69.4 70.0 68.3 68.9 70.0',
);

$alcances_sem_colisao_dp = array();
foreach ($alcances_sem_colisao as $lancamento_id => $alcance) {
    $medida = new MedidaPorDesvioPadrao();
    $medida->setValores($alcance)
           ->setOrdem(-2);
    $alcances_sem_colisao_dp[$lancamento_id] = $medida;
}

?>
