<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoletoEstudiantil extends Tarjeta implements TarjetaInterface{
   
	protected $tiempo_de_espera = 300; //5 minutos
    
	protected $fecha_pagada = NULL;
	
	public function valorBoleto(){
		return $this->valor/2;
	}
}
