<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoletoUniversitario extends Tarjeta implements TarjetaInterface{
   
	protected $tiempoDeEspera = 300; //5 minutos
    
	protected $ultimaFechaPagada = NULL;
	
	protected $mediosUsados = 0;

	public function valorBoleto(){
		return $this->valor/2;
	}

/* si pasaron 5 minutos, no se puede pagar con el medio voleto. 
si ya se usaron los 2 medios diarios, se paga el valor completo.*/
	public function pagoBoleto() {
		if($this->medioDisponible()){
			if($this->ultimaFechaPagada == NULL || $this->tiempoDeEsperaCumplido()){
				if($this->obtenerSaldo() >= $this->valorBoleto()){
					$this->pagarBoleto();
					return TRUE;
				}
				return $this->pagoBoletoConPlus();
			}

			return FALSE;
		}

		if($this->obtenerSaldo() >= $this->valorBoletoCompleto()){
			$this->pagarBoleto();
			return TRUE;
		}
		return $this->pagoBoletoConPlus();

	}

	public function pagarBoleto(){

		$this->saldo -= $this->valorBoleto();
		$tiempoNuevo = $this->tiempo->time();
		$this->ultimaFechaPagada = $tiempoNuevo;
		$this->mediosUsados++;
	}

	public function tiempoDeEsperaCumplido(){

        $ultimaFechaPagada = $this->obtenerUltimaFechaPagada();
        $fechaActual = $this->tiempo->time();
		$diferenciaDeFechas = $fechaActual - $ultimaFechaPagada;
		
        if($diferenciaDeFechas >= $this->obtenerTiempoDeEspera())
            return TRUE;
        
        return FALSE;
	}

	public function medioDisponible(){
		if($this->mediosUsados < 2){
			return TRUE;
		}
		if($this->tiempoDeEsperaUltimoMedioCumplido()){
		  $this->mediosUsados = 0;
		  return TRUE;
		}
		return FALSE;
  	}

	public function tiempoDeEsperaUltimoMedioCumplido(){
        $ultimaFechaPagada = $this->obtenerUltimaFechaPagada();
        
        $ultimaFechaPagada = date("d/m/y", $ultimaFechaPagada);
    
        $fechaActual = $this->tiempo->time();
        $fechaActual = date("d/m/y", $fechaActual);
            
        if($ultimaFechaPagada < $fechaActual){
             return TRUE;
        }
        return FALSE;
	}
	
	public function obtenerTiempoDeEspera(){
		return $this->tiempoDeEspera;
	}

	public function obtenerUltimaFechaPagada(){
	  return $this->ultimaFechaPagada;
	}
}
