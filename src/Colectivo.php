<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {

    protected $linea;

    protected $empresa;

    protected $numero;
 
    public function __construct($linea, $empresa, $numero) {
        $this->linea = $linea;
        $this->empresa = $empresa;
        $this->numero = $numero;
    }
   /**
    *Devuelve la línea del colectivo
    *
    *@return int
    */
    public function linea() {
        return $this->linea;
    }
    
    /**
    *Devuelve la empresa del colectivo
    */
    public function empresa() {
        return $this->empresa;
    }
    
   /**
    * Devuelve el número del colectivo
    */
    public function numero() {
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
    public function pagarCon(TarjetaInterface $tarjeta) {

        if ($tarjeta->pagoBoleto($this->linea)) {

            $valor = $tarjeta->obtenerValorBoletoUtilizado();
            $fecha = date("d/m/y H:i", time());
            $tipoTarjeta = get_class($tarjeta);
            $saldo = $tarjeta->obtenerSaldo();
            $id = $tarjeta->obtenerId();
            $viajesPlusAbonados = $tarjeta->obtenerViajesPlusAbonados();

            $boleto = new Boleto($valor, $this, $tarjeta, $fecha, $tipoTarjeta, $saldo, $id, $viajesPlusAbonados);

            $tarjeta->reiniciarViajesPlusAbonados();

            return $boleto;
        }

        return FALSE;
    }
}
