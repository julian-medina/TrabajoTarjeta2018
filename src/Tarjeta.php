<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    protected $saldo;
	protected $viajesPlus = 2;

    public function recargar($monto) {
		$valor = 14.80;
	// Esto estaba hecho mal a proposito.
		if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100) {
			$this->saldo += $monto;
		}
		if($monto == 510.15)
			$this->saldo += $monto + 81.93;
		if($monto == 962.59)
			$this->saldo += $monto + 221.58;

		if($this->viajesPlus == 0){
			if($this->saldo >= $valor){
				$this->saldo -= $valor;
				$this->viajesPlus = 1;
			}
		}

		if($this->viajesPlus == 1){
			if($this->saldo >= $valor){
				$this->saldo -= $valor;
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
	
	public function pagarVoleto($valor){
		$this->saldo -= $valor;
	}

	public function obtenerViajesPlus() {
		return $this->viajesPlus;
  	}

 	public function PagarViajesPlus() {
		$this->viajesPlus -= 1;
	}

}

class MedioBoleto extends Tarjeta {
	public function pagarVoleto($valor){
		$this->saldo -= $valor/2;
	}
}

class Jubilados extends Tarjeta {
	public function pagarVoleto($valor){}
}
