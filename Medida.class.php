<?php

Class Medida {

    protected $valor;
    protected $incerteza;
    protected $ordem;
    protected $valor_formatado;
    protected $incerteza_formatada;
    protected $name;
    protected $short_name;
    protected $modo_incerteza;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setTexName($sn) {
        $this->tex_name = $sn;
        return $this;
    }

    public function getTexName() {
        if ($this->tex_name) {
            return $this->tex_name;
        } else {
            return $this->getName();
        }
    }

    public function setValor($value) {
        $this->valor = $value;
        return $this;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setIncerteza($value) {
        $this->incerteza = $value;
        $this->setModoIncerteza('manual');
        return $this;
    }

    public function getIncerteza() {
        return $this->incerteza;
    }

    public function setModoIncerteza($modo) {
        $this->modo_incerteza = $modo;
    }

    public function getModoIncerteza() {
        if ($this->modo_incerteza) {
            return $this->modo_incerteza;
        } else {
            return 'sem incerteza';
        }
    }

    public function setOrdem($value) {
        $this->ordem = $value;
        return $this;
    }

    public function getOrdem() {
        if (isset($this->ordem) and $this->ordem) {
            return $this->ordem;
        } else {
            return 0;
        }
    }

    protected function setValorFormatado($value) {
        $this->valor_formatado = $value;
    }

    public function getValorFormatado() {
        $this->formatarIncerteza();
        return $this->valor_formatado;
    }

    public function getSI() {
        $valorSI = $this->getValorFormatado() * pow(10, $this->getOrdem());
        if ($this->getIncertezaSI() != 0) {
            $ordemIncerteza = $this->getOrdemPrimeiroDigitoSignificativo($this->getIncertezaSI());
            $valor_formatado = number_format($valorSI, -$ordemIncerteza, '.', '');
        } else {
            $valor_formatado = $valorSI;
        }
        return $valor_formatado;
    }

    public function getIncertezaSI() {
        return $this->getIncertezaFormatada() * pow(10, $this->getOrdem());
    }

    protected function setIncertezaFormatada($value) {
        $this->incerteza_formatada = $value;
    }

    public function getIncertezaFormatada() {
        $this->formatarIncerteza();
        return $this->incerteza_formatada;
    }

    public function getValorNotacaoCientifica($casasDecimais) {
        return $this->number_scientific($this->getValorFormatado(), $casasDecimais);
    }

    protected function formatarIncerteza() {
        $valor = $this->getValor();
        $incerteza = $this->getIncerteza();
        if ($incerteza != 0) {
            $ordem = $this->getOrdemPrimeiroDigitoSignificativo($incerteza);
            $valor_formatado = number_format($this->arredondar($valor, -$ordem), -$ordem, '.', '');
            $this->setValorFormatado($valor_formatado);
            $this->setIncertezaFormatada($this->arredondar($incerteza, -$ordem));
        } else {
            $this->setValorFormatado($this->getValor());
            $this->setIncertezaFormatada($this->getIncerteza());
        }
    }

    public function formatarIncertezaPercentual($porcentagem, $manual) {
        $valor = $this->getValor();
        $incerteza = $valor * $porcentagem;
        //$ordem = $this->getOrdemPrimeiroDigitoSignificativo($incerteza);
        //$incerteza = $incerteza + $digitos*pow(10, $ordem);
        $incerteza = $incerteza + $manual;
        $ordem = $this->getOrdemPrimeiroDigitoSignificativo($incerteza); // Algarismo significativo pode ter mudado após o acréscimo da linha anterior.
        $this->setIncerteza($incerteza);
        $this->setValorFormatado($this->number_significant($this->arredondar($valor, $ordem),$ordem));
        $this->setIncertezaFormatada($this->arredondar($incerteza, $ordem));
        $this->setModoIncerteza(100*$porcentagem . '% + ' . $manual);
        return $this;
    }

    protected function getOrdemPrimeiroDigitoSignificativo($valor) {
        $ordem = 0;
        if ($valor == 0) {
            throw new Exception('Valor nulo não possui dígito significativo.');
        } else {
            if ($valor < 1) {
                do {
                    $ordem--;
                    $valor *= 10;
                } while ($valor<1);
            } elseif ($valor >= 10) {
                do {
                    $ordem++;
                    $valor /= 10;
                } while ($valor>=10);   
            }
        }
        return $ordem;
    }

    protected function arredondar($x, $casasDecimaisDesejadas) {
        //return round($x, $casasDecimaisDesejadas, PHP_ROUND_HALF_EVEN);
        return round($x, $casasDecimaisDesejadas);
    }


    protected function number_significant($x, $n) {
        $x_sci = sprintf("%.".$n."e", $x);
        $x_f = rtrim(sprintf("%f", $x_sci),"0");
        if ( $x_f[strlen($x_f)-1]=="." ) {
            $x_f .= "0";
        }
        return $x_f;
    }

    protected function number_scientific($valor, $casasDecimais) {
        return sprintf("%.{$casasDecimais}E", $valor);
    }

    public function getFullSI($separador = '&#177;') {
        return "{$this->getSI()} $separador {$this->getIncertezaSI()}";
    }

    public function getNameAndSI() {
        return $this->getName() . ': ' . $this->getFullSI() . ' (' . $this->getModoIncerteza() . ')';
    }
}

?>
