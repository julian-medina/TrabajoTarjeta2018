<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
  protected $saldo = 0;
  protected $viajesPlus = 2;
  protected $valor = 14.80;
  protected $viajesPlusAbonados = 0;
  protected $tiempo;
  protected $id;
  protected $ultimoViajeFueTrasbordo = FALSE;
  protected $horaUltimoViaje = NULL;
  protected $ultimoColectivo = NULL;
  protected $ultimoValorPagado = NULL;

  public function __construct(TiempoInterface $tiempo, $id) {
    $this->tiempo = $tiempo;
    $this->id = $id;
    }

    public function recargar($monto) {

    if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100) {
      $this->saldo += $monto;
      $this->pagarValorViajesPlus();
      return TRUE;
    }

    if ($monto == 510.15) {
      $this->saldo += $monto + 81.93;
      $this->pagarValorViajesPlus();
      return TRUE;
    }

    if ($monto == 962.59) {
      $this->saldo += $monto + 221.58;
      $this->pagarValorViajesPlus();
      return TRUE;
    }

        return FALSE;
  }
	
    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo() {
        return $this->saldo;
  }

  public function obtenerValorBoletoUtilizado() {
    return $this->ultimoValorPagado;
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
    if ($this->viajesPlusAbonados != 0) {
          $this->viajesPlusAbonados = 0;
    }
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
  public function valorBoleto() {
    return $this->valor;
  }
	
  public function valorBoletoCompleto() {
    return $this->valor;
  }
	
  public function pagarValorViajesPlus() {
		
    if ($this->viajesPlus == 0) {
      if ($this->saldo >= $this->valor) {
        $this->saldo -= $this->valor;
        $this->viajesPlus = 1;
        $this->viajesPlusAbonados += 1;
      }
    }

    if ($this->viajesPlus == 1) {
      if ($this->saldo >= $this->valor) {
        $this->saldo -= $this->valor;
        $this->viajesPlus = 2;
        $this->viajesPlusAbonados += 1;
      }
    }

  }
  public function pagarBoleto($valorBoleto) {
    $this->saldo -= $valorBoleto;
  }
	
  /* tiene que asignar $horaUltimoViaje y $ultimoColectivo */
  public function pagoBoleto($linea) {

    $valorBoleto = $this->calcularValorBoleto($linea);
    if ($this->obtenerSaldo() >= $valorBoleto) {
      $this->pagarBoleto($valorBoleto);
      $this->ultimoValorPagado = $valorBoleto; //Se guarda cuento pago
            $this->ultimoColectivo = $linea;
      $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
      $this->ultimoViajeFueTrasbordo = FALSE;

      if ($valorBoleto == $this->valorBoleto()*0.33) {
        //guarda que se uso el trasbordo en la ultima vez.
        $this->ultimoViajeFueTrasbordo = TRUE;
      }

      return TRUE;
    }

    return $this->pagoBoletoConPlus($linea);
  }

  public function pagoBoletoConPlus($linea) {

    if ($this->obtenerViajesPlus() == 2) {
      $this->pagarViajesPlus();

      $this->primerPlusUsado();
      $this->ultimoValorPagado = 0.0; //Se guarda cuento pago
            $this->ultimoColectivo = $linea;
            $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
            return TRUE;
    }

    if ($this->obtenerViajesPlus() == 1) {
      $this->pagarViajesPlus();
			
      $this->ultimoPlusUsado();
      $this->ultimoValorPagado = 0.0; //Se guarda cuento pago
            $this->ultimoColectivo = $linea;
            $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transaccion
            return TRUE;
    }

    return FALSE;
  }

  public function calcularValorBoleto($linea) {
        return $this->trasbordo($linea, $this->valorBoleto());
  }
	
    protected function trasbordo($linea, $valorBoleto) {

        if ($this->ultimoColectivo == $linea || $this->ultimoValorPagado == 0.0 || $this->ultimoViajeFueTrasbordo) {
            return $valorBoleto;
    }
    /* Cuando no es feriado, de lunes a viernes de 6 a 22 o sabados de 6 a 14 */
    if (((date('N', $this->tiempo->time()) <= 5 && date('G', $this->tiempo->time()) > 6 && date('G', $this->tiempo->time()) < 22) 
    || (date('N', $this->tiempo->time()) == 6 && date('G', $this->tiempo->time()) > 6 && date('G', $this->tiempo->time()) < 14))
     && (!$this->tiempo->feriado())) {
        //hasta 60 minutos
            if (($this->tiempo->time() - $this->horaUltimoViaje) < 3600) {
                return ($valorBoleto*0.33);
            }
        } //en el resto de los casos, hasta 90 minutos para trasbordo
        else {
            if (($this->tiempo->time() - $this->horaUltimoViaje) < 5400) {
                return ($valorBoleto*0.33);
            }
    }
		
        return $valorBoleto;
  }
	
  public function obtenerTrasbordo() {
    return $this->ultimoViajeFueTrasbordo;
  }

}
