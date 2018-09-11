<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;

    public function __construct($linea, $empresa, $numero){
        $this->linea = $linea;
        $this->empresa = $empresa;
        $this->numero = $numero;
    }
    
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
/*
        if(get_class($tarjeta) === "TrabajoTarjeta\MedioBoleto"){
            $valor /= 2;
        }

        if($tarjeta->obtenerSaldo() >= $valor){
            $tarjeta->pagarVoleto($valor);
            $boleto = new Boleto($valor, $this, $tarjeta);
            return $boleto;
        }

        if($tarjeta->obtenerViajesPlus() == 2 || $tarjeta->obtenerViajesPlus() == 1){
            $tarjeta->PagarViajesPlus();
            $boleto = new Boleto($valor, $this, $tarjeta);
            return $boleto;
        }
*/
        $valor_boleto = $tarjeta->obtenerValorBoleto();
        $viajesPlusUsados = $tarjeta->obtenerViajesPlusUsados();
        if($tarjeta->pagar()){
            $boleto = new Boleto($valor_boleto, $this, $tarjeta, date("d/m/y H:i", time()),
            get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(),
            $viajesPlusUsados-$tarjeta->obtenerViajesPlusUsados());
            return $boleto;
        }
        return False;  
    }
}
