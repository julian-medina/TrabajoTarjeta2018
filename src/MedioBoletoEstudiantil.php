<?php

namespace TrabajoTarjeta;

class MedioBoletoEstudiantil extends Tarjeta implements TarjetaInterface {
   
  protected $tiempoDeEspera = 300; //5 minutos
    
  protected $ultimaFechaPagada = NULL;
	
  public function valorBoleto() {
    return $this->valor/2;
  }

/* si no pasaron 5 minutos, no se puede pagar con el medio boleto. */
  public function pagoBoleto($linea) {
    if ($this->ultimaFechaPagada == NULL || $this->tiempoDeEsperaCumplido()) {

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

    return FALSE;
  }
    /**
     * Paga un boleto, actualiza el momento de la operación.
     *
     * @param float $valor
     *
     * @return void
     */
  public function pagarBoleto($valorBoleto) {

    $this->saldo -= $valorBoleto;
    $tiempoNuevo = $this->tiempo->time();
    $this->ultimaFechaPagada = $tiempoNuevo;
  }
    /**
     * Chequea si el tiempo entre usos del medio boleto ya pasó.
     * 
     * @return void
     */
  public function tiempoDeEsperaCumplido() {

        $ultimaFechaPagada = $this->obtenerUltimaFechaPagada();
        $fechaActual = $this->tiempo->time();
    $diferenciaDeFechas = $fechaActual - $ultimaFechaPagada;
		
        if ($diferenciaDeFechas >= $this->obtenerTiempoDeEspera()) {
                    return TRUE;
        }
        
        return FALSE;
  }

  // Obtiene el tiempo de espera entre usos del medio boleto.
  public function obtenerTiempoDeEspera() {
    return $this->tiempoDeEspera;
  }

  // Devuelve el último momento de uso del medio boleto.
  public function obtenerUltimaFechaPagada() {
    return $this->ultimaFechaPagada;
  }
}
