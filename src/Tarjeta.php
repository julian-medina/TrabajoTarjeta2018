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

	public function obtenerValorBoletoUtilizado(){
		return $this->valorBoleto();
	  }

	public function obtenerId() {
		return $this->id;
  }
	
  public function obtenerViajesPlus() {
		return $this->viajesPlus;
	}

	public function obtenerViajesPlusAbonados() {
		return $this->viajesPlusAbonados;
	}
	
	public function reiniciarViajesPlusAbonados() {
		if($this->viajesPlusAbonados != 0)
			$this->viajesPlusAbonados = 0;
	}

	public function primerPlusUsado() {
		$this->viajesPlusAbonados = -1;
	}

	public function ultimoPlusUsado() {
		$this->viajesPlusAbonados = -2;
	}

	public function pagarViajesPlus() {
		$this->viajesPlus -= 1;
	}
	public function valorBoleto(){
		return $this->valor;
	}
	
	public function valorBoletoCompleto(){
		return $this->valor;
	}
	
	public function pagarBoleto(){
		$this->saldo -= $this->valorBoleto();
	}

	public function pagoBoleto() {

		if($this->obtenerSaldo() >= $this->valorBoleto()){
            $this->pagarBoleto();
			return TRUE;
		}

		return $this->pagoBoletoConPlus();
	}

	public function pagoBoletoConPlus() {
		if($this->obtenerViajesPlus() == 2){
			$this->pagarViajesPlus();

			$this->primerPlusUsado();
			
            return TRUE;
		}

		if($this->obtenerViajesPlus() == 1){
			$this->pagarViajesPlus();
			
			$this->ultimoPlusUsado();

            return TRUE;
		}

		return FALSE;
	}

}
