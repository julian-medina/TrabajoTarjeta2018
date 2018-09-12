<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoleto extends Tarjeta implements TarjetaInterface{
    public function pagarBoleto(){
        $this->saldo -= $this->$valor/2;
    }
}
