<?php

require_once('Medida.class.php');
require_once('MedidaComposta.class.php');
require_once('MedidaPorDesvioPadrao.class.php');

class Medidas {

    protected $medidas = array();

    public function addObjetoMedida($medida) {
        $this->medidas[$medida->getName()] = clone $medida;
        return $this->medidas[$medida->getName()];
    }

    public function addMedida($name) {
        $this->medidas[$name] = new Medida($name);
        return $this->medidas[$name];
    }

    public function addMedidaComposta($name) {
        $this->medidas[$name] = new MedidaComposta($name);
        $this->medidas[$name]->setMedidasSource($this);
        return $this->medidas[$name];
    }

    public function addMedidaPorDesvioPadrao($name) {
        $this->medidas[$name] = new MedidaPorDesvioPadrao($name);
        return $this->medidas[$name];
    }

    public function getMedida($name) {
        return $this->medidas[$name];
    }

    public function getMedidas() {
        return $this->medidas;
    }

    public function debugAll() {
        foreach ($this->getMedidas() as $medida) {
            echo $medida->getNameAndSI() . "<br>";
        }
    }

}

?>
