<?php

Class MedidaPorDesvioPadrao extends Medida {

    protected $valores = array();
    protected $media;
    protected $desvioPadrao;

    public function setValores($valores) {
        if (is_string($valores)) {
            $espacos_corrigidos = trim(preg_replace('/\s+/', ' ',$valores));
            $valores = explode(' ', $espacos_corrigidos);
        }
        if (is_array($valores)) {
            $this->valores = $valores;
        } else {
            throw new Exception ('Arguamento deve ser array ou string separada por espaços.');
        }
        return $this;
    }

    public function addValor($valor) {
        $this->valores[] = $valor;
        return $this;
    }

    public function getValores() {
        return $this->valores;
    }

    protected function setMedia($valor) {
        $this->media = $valor;
    }

    protected function setDesvioPadrao($valor) {
        $this->desvioPadrao = $valor;
    }

    public function getMedia() {
        $this->calcularMediaEDesvio();
        return $this->media;
    }

    public function getValor() {
        return $this->getMedia();
    }

    public function getDesvio() {
        $this->calcularMediaEDesvio();
        return $this->desvioPadrao;
    }

    public function getIncerteza() {
        return $this->getDesvio();
    }

    protected function calcularMediaEDesvio() {
        $total = 0;
        $valores = $this->getValores();
        if (count($valores)>1) {
            foreach ($valores as $valor) {
                $total += $valor;
            }
            $media = $total/count($valores);
            $somatoriaQuadrados = 0;
            foreach ($valores as $chave => $valor) {
                $quadradoDaDiferenca = pow(($valor - $media), 2);
                $somatoriaQuadrados += $quadradoDaDiferenca;
            }
            $incerteza = $somatoriaQuadrados/(count($valores)*(count($valores)-1));
            $incerteza = pow($incerteza, 0.5);
            $this->setDesvioPadrao($incerteza);
            $this->setMedia($media);
        }
    }
}

?>
