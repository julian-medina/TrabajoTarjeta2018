<?php

namespace TrabajoTarjeta;

class Tiempo implements TiempoInterface{
    
    public function time(){
        return time();
    }
}