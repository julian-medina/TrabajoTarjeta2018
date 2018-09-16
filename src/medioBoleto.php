<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoleto extends Tarjeta implements TarjetaInterface{
    public function valorBoleto(){
		return $this->valor/2;
	}
}
