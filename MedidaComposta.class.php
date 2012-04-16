<?php

class MedidaComposta extends Medida {

    protected $medidas = array();
    protected $composicaoIncerteza = array();

    public function __construct($name) {
        $this->maximaCacheFilename = 'maxima.cache';
        parent::__construct($name);
        return $this;
    }

    public function setMedidas($medidas) {
        // Array com objetos da classe Medida
        $this->medidas = array();
        $this->addMedida($medidas);
        return $this;
    }

    public function addMedida($medidas) {
        // Array com objetos da classe Medida
        foreach ($medidas as $medida) {
            $medidaComIndice = array($medida->getName() => $medida);
            $this->medidas = array_merge($this->medidas, $medidaComIndice);
        }
        return $this;
    }

    public function setExpression($expression) {
        $this->expression = $expression;
        return $this;
    }

    public function getExpression() {
        return $this->expression;
    }

    public function getValor() {
        return $this->evaluateExpression($this->getExpression());
    }

    public function getIncerteza() {
        $this->composicaoIncerteza = array();
        $termos = array();
        foreach ($this->medidas as $medida) {
            $var = $medida->getName();
            $derivadaParcial = $this->derivadaParcial($var);
            $valorDerivadaParcial = $this->evaluateExpression($derivadaParcial);
            $termo = $medida->getIncertezaSI() * $valorDerivadaParcial;
            $termos[$var] = $termo;
        }
        $somaQuadrados = 0;
        foreach ($termos as $termo) {
           $somaQuadrados += pow($termo,2);
        }
        foreach ($termos as $var => $termo) {
            $this->composicaoIncerteza[$var] = pow($termo,2)/$somaQuadrados;
        }
        return sqrt($somaQuadrados);
    }

    public function getComposicaoIncerteza($var = false) {
        if ($var) {
            return $this->composicaoIncerteza[$var];
        } else {
            return $this->composicaoIncerteza;
        }
    }

    public function debugComposicaoIncerteza() {
        echo "<br><br>";
        echo "Detalhes da composição da incerteza de {$this->name}:";
        echo "<br>";
       foreach ($this->getComposicaoIncerteza() as $var => $porcentagem) {
            $porcentagem = number_format($porcentagem, 2);
            echo "$var => $porcentagem; ";
       }
       echo "<br><br>";
    }

    public function derivadaParcial($variable) {
        // Usar notação do Maxima
        $expression = preg_replace('~\$(.*)\$~U', '($1)', $this->getExpression());
        $comandoMaxima = "diff($expression,$variable)";
        $this->getCacheMaxima($comandoMaxima);
        if (!($derivada = $this->getCacheMaxima($comandoMaxima))) {
            exec("maxima --very-quiet --run-string='stardisp:true$ display2d:false$ $comandoMaxima;'", $derivada, $returnStatus);
            if ($returnStatus != 0) {
                throw new Exception('Maxima returned an error.');
            } else {
                $derivada = trim(implode(' ', $derivada));
                $derivada = str_replace(' ', '', $derivada);
                foreach ($this->medidas as $medida) {
                    $var = $medida->getName();
                    $derivada = preg_replace("~([^A-Za-z]+?|^)$var([^A-Za-z]+?|$)~", "$1\$$var\$$2", $derivada);
                }
                $this->setCacheMaxima($comandoMaxima, $derivada);
            }
        }
        return $derivada;
    }

    protected function getCacheMaxima($comandoMaxima) {
        $comandoMaxima = trim($comandoMaxima);
        $comandoMaxima = str_replace(' ', '', $comandoMaxima);
        if (!isset($GLOBALS['cacheMaxima'])) {
            $this->reloadCacheMaxima();
        }
        $linhasCache = $GLOBALS['cacheMaxima'];
        foreach ($linhasCache as $linha) {
            if (strpos($linha, ':::') !== FALSE) {
                $partes = explode(':::', $linha); 
                $comando = trim($partes[0]);
                $resposta = trim($partes[1]);
                if ($comandoMaxima == $comando) {
                   return $resposta; 
                }
            }
        }
        return false;
    }

    protected function reloadCacheMaxima() {
        // Lê o arquivo do cache e salva na memória, para que o acesso seja rápido
        $GLOBALS['cacheMaxima'] = file($this->maximaCacheFilename);
    }

    protected function setCacheMaxima($comandoMaxima, $resposta) {
        $comandoMaxima = trim($comandoMaxima);
        $resposta = trim($resposta);
        file_put_contents($this->maximaCacheFilename, "$comandoMaxima ::: $resposta\n", FILE_APPEND);
        $this->reloadCacheMaxima();
    }

    public function evaluateExpression($expression) {
        $original_expression = $expression;

        $expression_no_spaces = str_replace(' ', '', $expression); 

        $posInicio = false;
        $posFim = false;

        while (($pos = strpos($expression, '^')) !== FALSE) {

            // Encontrar base do expoente
            $i = $pos;
            $identacao = 0;
            while (true) {
                $i--;
                if ($i < 0) {
                    $posInicio = 0;
                    break;
                }
                switch ($expression[$i]) {
                    case ')':
                        $identacao++;
                        break;
                    case '(':
                        $identacao--;
                        if ($identacao < 0) {
                            $posInicio = $i + 1;
                            break 2;
                        }
                        break;
                    case '*':
                    case '-':
                        if ($expression[$i+1] == '>') break;
                    case '+':
                    case '/':
                        if ($identacao == 0) {
                            $posInicio = $i + 1;
                            break 2;
                        }
                        break;
                }
            }

            // Encontrar expoente
            $i = $pos;
            $identacao = 0;
            while (true) {
                $i++;
                if ($i >= strlen($expression)) {
                    $posFim = strlen($expression);
                    break;
                }
                switch ($expression[$i]) {
                    case '(':
                        $identacao++;
                        break;
                    case ')':
                        $identacao--;
                        if ($identacao < 0) {
                            $posFim = $i - 1;
                            break 2;
                        }
                        break;
                    case '*':
                    case '-':
                        if ($expression[$i+1] == '>') break;
                    case '+':
                    case '/':
                        if ($identacao == 0) {
                            $posFim = $i - 1;
                            break 2;
                        }
                        break;
                }
            }
            $base = substr($expression, $posInicio, $pos - $posInicio);
            $expoente = substr($expression, $pos+1, $posFim - $pos);
            $comando = "pow($base, $expoente)";
            $expression = str_replace($base . '^' . $expoente, $comando, $expression);
        }

        $intermediate_expression = $expression;
        $expression = preg_replace('/\$(.*)\$/U', '(\$this->medidas["$1"]->getSI())', $expression);

        ob_start();
        if (eval("\$valor = $expression;") === FALSE) {
            echo "<br>Erro ao parsear expressão. Passos do parser:";
            echo "<br><br>" . $original_expression . "<br><br>";
            echo $intermediate_expression . "<br><br>";
            echo $expression . "<br><br>";
            echo "<hr>";
        }
        $output_eval = ob_get_clean();
        if (false and $output_eval and strpos($output_eval, 'Notice') !== FALSE) {
            echo "<hr>";
            echo "Deu erro em:<br><br>";
            echo "$original_expression<br><br>";
            echo "$intermediate_expression<br><br>";
            echo "$expression<br><br>";
            echo "$output_eval<br><br>";
            echo "<hr>";
        }
        return $valor;
    }
}

?>
