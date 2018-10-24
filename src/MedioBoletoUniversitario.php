<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class MedioBoletoUniversitario extends Tarjeta implements TarjetaInterface {
   
	protected $tiempoDeEspera = 300; //5 minutos
    
	protected $mediosUsados = 0;

	public function valorBoleto() {
		if ($this->medioDisponible()) {
			return $this->valor/2;
		}

		return $this->valor;
	}

/* si pasaron 5 minutos, no se puede pagar con el medio boleto. 
si ya se usaron los 2 medios diarios, se paga el valor completo.*/
	public function pagoBoleto($linea) {
		if ($this->medioDisponible()) {
			if ($this->horaUltimoViaje == NULL || $this->tiempoDeEsperaCumplido()) {

				$valorBoleto = $this->calcularValorBoleto($linea);

				if ($this->obtenerSaldo() >= $valorBoleto) {
					$this->pagarBoleto($valorBoleto);

					$this->ultimoValorPagado = $valorBoleto; //Se guarda cuento pago
					$this->ultimoColectivo = $linea;
					$this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
					$this->ultimoViajeFueTrasbordo = FALSE;

					if ($valorBoleto == $this->valorBoleto()*0.33) //guarda que se uso el trasbordo en la ultima vez.
						$this->ultimoViajeFueTrasbordo = TRUE;
					
					$this->mediosUsados++;

					return TRUE;
				}
				return $this->pagoBoletoConPlus($linea);
			}

			return FALSE;
		}

		$valorBoleto = $this->calcularValorBoleto($linea);
		if ($this->obtenerSaldo() >= $valorBoleto) {
			$this->pagarBoleto($valorBoleto);
			$this->ultimoValorPagado = $valorBoleto; //Se guarda cuento pago
            $this->ultimoColectivo = $linea;
			$this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
			$this->ultimoViajeFueTrasbordo = FALSE;

			if ($valorBoleto == $this->valorBoleto()*0.33) //guarda que se uso el trasbordo en la ultima vez.
				$this->ultimoViajeFueTrasbordo = TRUE;

			return TRUE;
		}
		return $this->pagoBoletoConPlus($linea);

	}

	public function pagarBoleto($valorBoleto) {
		$this->saldo -= $valorBoleto;
	}

	public function tiempoDeEsperaCumplido() {

		$fechaActual = $this->tiempo->time();
		$diferenciaDeFechas = $fechaActual - $this->horaUltimoViaje;
		
        if ($diferenciaDeFechas >= $this->obtenerTiempoDeEspera())
            return TRUE;
        
        return FALSE;
	}

	public function medioDisponible() {
		if ($this->mediosUsados < 2)
			return TRUE;
		
		if ($this->tiempoDeEsperaUltimoMedioCumplido()) {
		  $this->mediosUsados = 0;
		  return TRUE;
		}

		return FALSE;
  	}

	public function tiempoDeEsperaUltimoMedioCumplido() {
        
        $ultimaFechaPagada = date("d/m/y", $this->horaUltimoViaje);
        $fechaActual = date("d/m/y", $this->tiempo->time());
            
        if ($ultimaFechaPagada < $fechaActual) {
             return TRUE;
        }
        return FALSE;
	}
	
	public function obtenerTiempoDeEspera() {
		return $this->tiempoDeEspera;
	}

}
