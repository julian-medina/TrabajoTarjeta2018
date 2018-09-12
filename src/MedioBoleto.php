<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoleto extends Tarjeta implements TarjetaInterface{
    public function pagarBoleto($valor){
        $this->saldo -= $valor/2;
    }
}
