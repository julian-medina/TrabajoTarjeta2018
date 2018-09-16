<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoletoEstudiantil extends Tarjeta implements TarjetaInterface{
   
	protected $tiempoDeEspera = 300; //5 minutos
    
	protected $ultimaFechaPagada = NULL;
	
	public function valorBoleto(){
		return $this->valor/2;
	}

/* si pasaron 5 minutos, no se puede pagar con el medio voleto. */
	public function pagoBoleto() {
		if($this->ultimaFechaPagada == NULL || $this->tiempoDeEsperaCumplido()){
			if($this->obtenerSaldo() >= $this->valorBoleto()){
				$this->pagarBoleto();
				return TRUE;
			}
			return FALSE;
		}

		return FALSE;
	}

	public function pagarBoleto(){

		$this->saldo -= $this->valorBoleto();
		$tiempoNuevo = $this->tiempo->time();
		$this->ultimaFechaPagada = $tiempoNuevo;
	}

	public function tiempoDeEsperaCumplido(){

        $ultimaFechaPagada = $this->obtenerUltimaFechaPagada();
        $fechaActual = $this->tiempo->time();
		$diferenciaDeFechas = $fechaActual - $ultimaFechaPagada;
		
        if($diferenciaDeFechas >= $this->obtenerTiempoDeEspera())
            return TRUE;
        
        return FALSE;
	}


	public function obtenerTiempoDeEspera(){
		return $this->tiempoDeEspera;
	}

	public function obtenerUltimaFechaPagada(){
	  return $this->ultimaFechaPagada;
	}
}
