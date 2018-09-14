<?php

namespace TrabajoTarjeta;

class TiempoFalso implements TiempoInterface{

    protected $tiempo;

    public function __construct($tiempoInicial = 0){
        $this->tiempo = $tiempoInicial;
    } 

    public function avanzar($segundos){
        $this->tiempo += $segundos;
    }

    public function time(){
        return $this->tiempo;
    }
}