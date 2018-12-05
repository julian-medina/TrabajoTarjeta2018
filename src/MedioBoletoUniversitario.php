<?php

namespace TrabajoTarjeta;


class MedioBoletoUniversitario extends Tarjeta implements TarjetaInterface {
   
  protected $tiempoDeEspera = 300; //5 minutos
  protected $ultimoValorPagado = 0;
  protected $ultimaFechaPagada = NULL;
  protected $ultimoColectivo = NULL;
  protected $horaUltimoViaje = NULL;
  protected $ultimoViajeFueTrasbordo = FALSE;
	
  //Devuelve el valor del boleto, teniendo en consideración la posibilidad del medio boleto.
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

          $this->ultimoValorPagado = $valorBoleto; //Se guarda cuánto pagó
          $this->ultimoColectivo = $linea;
          $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transacción
          $this->ultimoViajeFueTrasbordo = FALSE;

          if ($valorBoleto == $this->valorBoleto()*0.33) //guarda que se usó el trasbordo en el último viaje.
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
      $this->ultimoValorPagado = $valorBoleto; //Se guarda cuánto pagó
            $this->ultimoColectivo = $linea;
      $this->horaUltimoViaje = $this->tiempo->time(); //Se guarda la hora de la transacción
      $this->ultimoViajeFueTrasbordo = FALSE;

      if ($valorBoleto == $this->valorBoleto()*0.33) //guarda que se usó el trasbordo en la último viaje.
        $this->ultimoViajeFueTrasbordo = TRUE;

      return TRUE;
    }
    return $this->pagoBoletoConPlus($linea);

  }
	
 /**
  *resta el precio del boleto al saldo
  *(paga un boleto)
  */
  public function pagarBoleto($valorBoleto) {
    $this->saldo -= $valorBoleto;
  }

 /** Chequea si el tiempo de espera necesario entre usos del boleto se cumplió o no.
  *
  *  @return bool
  */
  public function tiempoDeEsperaCumplido() {

    $fechaActual = $this->tiempo->time();
    $diferenciaDeFechas = $fechaActual - $this->horaUltimoViaje;
		
        if ($diferenciaDeFechas >= $this->obtenerTiempoDeEspera())
            return TRUE;
        
        return FALSE;
  }

/**
 * Devuelve un bool que representa la disponibilidad del medio boleto
 *
 * @return bool
 */
  public function medioDisponible() {
    if ($this->mediosUsados < 2)
      return TRUE;
		
    if ($this->tiempoDeEsperaUltimoMedioCumplido()) {
      $this->mediosUsados = 0;
      return TRUE;
    }

    return FALSE;
    }

 /**
  *Comprueba que el tiempo necesario para volver a 
  *usar el medio boleto haya transcurrido
  */
  public function tiempoDeEsperaUltimoMedioCumplido() {
        
        $ultimaFechaPagada = date("d/m/y", $this->horaUltimoViaje);
        $fechaActual = date("d/m/y", $this->tiempo->time());
            
        if ($ultimaFechaPagada < $fechaActual) {
              return TRUE;
        }
        return FALSE;
  }
  // Devuelve el tiempo de espera entre usos del medio boleto.
  public function obtenerTiempoDeEspera() {
    return $this->tiempoDeEspera;
  }

}
