<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;

    public function linea(){
        return $this->linea;
    }

    public function empresa(){
        return $this->empresa;
    }

    public function numero(){
        return $this->numero;
    }

    /**
     * Paga un viaje en el colectivo con una tarjeta en particular.
     *
     * @param TarjetaInterface $tarjeta
     *
     * @return BoletoInterface|FALSE
     *  El boleto generado por el pago del viaje. O FALSE si no hay saldo
     *  suficiente en la tarjeta.
     */
    public function pagarCon(TarjetaInterface $tarjeta){
        $valor = 14.80;
        
        if($tarjeta->obtenerSaldo() >= $valor){
            /*             $tarjeta->obtenerSaldo() -= $valor;      
            no se puede modificar el saldo sin una funcion en clase Tarjeta*/
            $boleto = new Boleto($valor, $this, $tarjeta);
            return $boleto;
        }
        else {
            return False;
        }
    }
}
