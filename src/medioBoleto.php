<?php

namespace TrabajoTarjeta;
class MedioBoletoEstudiantil extends Tarjeta implements TarjetaInterface{
    
    protected $tiempoMedioInusable = 300;
    protected $momentoPagado = NULL;
    
    public function medioDisponible(){
        $fecha_ultima = $this->obtenerMomentoPagado();
       
   public function obtenerCooldown(){
          return $this->tiempoMedioInusable;
      }
      
   public function obtenerMomentoPagado(){
        return $this->momentoPagado;
      }
      
   public function obtenerValorMedio(){
        return $this->valor_boleto/2; public function obtenerValorMedio(){
        return $this->valor_boleto/2;
      }
    
   public function pagar() {
        if($this->saldo >= $this->obtenerValorMedio()){
            if($this->momentoPagado == NULL || $this->medioDisponible()){
                $this->saldo -= $this->obtenerValorMedio();
                $timer = $this->tiempo->time();
                $this->momentoPagado = $timer;
            }
            else{
                return FALSE;
            }
            return TRUE;
        }
        
}
