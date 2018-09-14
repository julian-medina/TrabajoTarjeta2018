<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class FranquiciaCompleta extends Tarjeta implements TarjetaInterface{

	public function pagoBoleto($colectivo){
        $boleto = new Boleto($this->valor, $colectivo, $this);
        return $boleto;
    }
}
