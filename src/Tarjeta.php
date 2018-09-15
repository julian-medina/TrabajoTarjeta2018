<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
	protected $saldo = 0;
	protected $viajesPlus = 2;
	protected $valor = 14.80;
	protected $viajesPlusAbonados = 0;
	protected $tiempo;
	protected $id;

	public function __construct(TiempoInterface $tiempo, $id){
		$this->tiempo=$tiempo;
		$this->id = $id;
	  }

    public function recargar($monto) {

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
				$this->viajesPlusAbonados += 1;
			}
		}

		if($this->viajesPlus == 1){
			if($this->saldo >= $this->valor){
				$this->saldo -= $this->valor;
				$this->viajesPlus = 2;
				$this->viajesPlusAbonados += 1;
			}
		}
	    	else if($this->viajesPlus == 2){
			viajesPlusAbonados = 0;

      	return $monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100 || $monto == 510.15 || $monto == 962.59;
	}
	
	/**public function abonaViajesPlus(){

		if($this->viajesPlus == 0){
			if($this->saldo >= $this->valor*2){
				$this->saldo -= $this->valor*2;
				$this->viajesPlus = 2;
				return TRUE;
			}
			return FALSE;
		}

		if($this->viajesPlus == 1){
			if($this->saldo >= $this->valor){
				$this->saldo -= $this->valor;
				$this->viajesPlus = 2;
				return TRUE;
			}
			return FALSE;
		}
		
		return TRUE;
	}
	*/
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

	public function valorBoletoCompleto(){
		return $this->valor;
	}

	public function pagoBoleto() {

		if($this->obtenerSaldo() >= $this->valorBoleto()){
            $this->pagarBoleto();
			return TRUE;
		}
		return FALSE;
	}

	public function pagoBoletoConPlus() {
        if($this->obtenerViajesPlus() == 2 || $this->obtenerViajesPlus() == 1){
            $this->PagarViajesPlus();
            return TRUE;
		}
		return False;
	}

}
