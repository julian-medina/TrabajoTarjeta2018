<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
	protected $saldo = 0;
	protected $viajesPlus = 2;
	protected $valor = 14.80;

    public function recargar($monto) {

	// Esto estaba hecho mal a proposito.
		if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100) {
			$this->saldo += $monto;
		}
		if($monto == 510.15)
			$this->saldo += $monto + 81.93;
		if($monto == 962.59)
			$this->saldo += $monto + 221.58;

		if($this->viajesPlus == 0){
			if($this->saldo >= $this->valor){
				$this->saldo -= $this->valor;
				$this->viajesPlus = 1;
			}
		}

		if($this->viajesPlus == 1){
			if($this->saldo >= $this->valor){
				$this->saldo -= $this->valor;
				$this->viajesPlus = 2;
			}
		}

      	return $monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100 || $monto == 510.15 || $monto == 962.59;
    }

    /**
	 * Devuelve el saldo que le queda a la tarjeta.
	 *
	 * @return float
	 */
    public function obtenerSaldo() {
      	return $this->saldo;
	}
	
	public function pagarBoleto(){
		$this->saldo -= $this->valorBoleto();
	}

	public function obtenerViajesPlus() {
		return $this->viajesPlus;
  	}

 	public function PagarViajesPlus() {
		$this->viajesPlus -= 1;
	}
	public function valorBoleto(){
		return $this->valor;
	}

	public function pagoBoleto($colectivo) {

		if($this->obtenerSaldo() >= $this->valorBoleto()){
            $this->pagarBoleto();
            $boleto = new Boleto($this->valorBoleto(), $colectivo, $this);
            return $boleto;
        }

        if($this->obtenerViajesPlus() == 2 || $this->obtenerViajesPlus() == 1){
            $this->PagarViajesPlus();
            $boleto = new Boleto($this->valor, $colectivo, $this);
            return $boleto;
		}

		return False;
	}

}
