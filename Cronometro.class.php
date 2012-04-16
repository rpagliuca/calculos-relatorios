<?php
class Cronometro {

    public function iniciar() {
        $this->initialTime = microtime(time());
    }

    public function parar() {
        return microtime(time()) - $this->initialTime;
    }

}
?>
