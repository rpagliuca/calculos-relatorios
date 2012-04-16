<?php

class Tabela {
    protected $dados;
    protected $columns = array();
    protected $rows = array();

    public function __construct($dados) {
        $this->dados = $dados;
    }

    public function render() {
        $output = '<table>';
        foreach ($this->dados as $linhas) {
            $output .= '<tr>'; 
            foreach ($linhas as $coluna) {
                $output .= "<td>$coluna</td>";
            }
            $output .= '</tr>';
        }
        $output .= '</table>';
        return $output;
    }
}

?>
