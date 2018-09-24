<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoletoUniversitario extends Tarjeta implements TarjetaInterface{
   
	protected $tiempoDeEspera = 300; //5 minutos
    
	protected $ultimaFechaPagada = NULL;
	
	protected $mediosUsados = 0;

	protected $valorUtilizado;

	public function valorBoleto(){
		if($this->medioDisponible()){
			$this->valorUtilizado = $this->valor/2;
			return $this->valor/2;
		}

		$this->valorUtilizado = $this->valor;
		return $this->valor;
	}

/* si pasaron 5 minutos, no se puede pagar con el medio boleto. 
si ya se usaron los 2 medios diarios, se paga el valor completo.*/
	public function pagoBoleto($linea) {
		if($this->medioDisponible()){
			if($this->ultimaFechaPagada == NULL || $this->tiempoDeEsperaCumplido()){

				$valorBoleto = $this->calcularValorBoleto($linea);

				if($this->obtenerSaldo() >= $valorBoleto){
					$this->pagarBoleto($valorBoleto);
					$this->ultimoValorPagado = $valorBoleto; //Se guarda cuento pago
					$this->ultimoColectivo = $linea;
					$this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
					return TRUE;
				}
				return $this->pagoBoletoConPlus($linea);
			}

			return FALSE;
		}

		if($this->obtenerSaldo() >= $this->valorBoletoCompleto()){
			$this->pagarBoleto($this->valorBoletoCompleto());
			$this->ultimoValorPagado = $this->valorBoletoCompleto(); //Se guarda cuento pago
            $this->ultimoColectivo = $linea;
            $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
			return TRUE;
		}
		return $this->pagoBoletoConPlus($linea);

	}

	public function pagarBoleto($valorBoleto){

		$this->saldo -= $valorBoleto;
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
		if($this->mediosUsados < 2)
			return TRUE;
		
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

	public function obtenerValorBoletoUtilizado(){
		return $this->valorUtilizado;
	  }
}
