<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface {

    protected $valor;

    protected $colectivo;

    protected $tarjeta;

    protected $fecha;

    protected $tipoTarjeta;

    protected $saldo;

    protected $id;

    protected $viajesPlus;

    public function __construct($valor, $colectivo, $tarjeta, $fecha, $tipoTarjeta, $saldo, $id, $viajesPlusAbonados) {
        $this->valor = $valor;
        $this->colectivo = $colectivo;
        $this->tarjeta = $tarjeta;
        $this->fecha = $fecha;
        $this->tipoTarjeta = $tipoTarjeta;
        $this->saldo = $saldo;
        $this->id = $id;
        $this->viajesPlus = $this->mostrarViajePlusEnBoleto($viajesPlusAbonados, $tarjeta);
    }

    public function mostrarViajePlusEnBoleto($viajesPlus, $tarjeta){
        if($viajesPlus>0){
            return "ABONA VIAJES PLUS: ".$viajesPlus*$tarjeta->valorBoletoCompleto();
        }

        if($viajesPlus==0){
            return "";
        }

        if($viajesPlus==-1){
            return "VIAJE PLUS";
        }

        return "Ult. PLUS";
    }

    /**
     * Devuelve el valor del boleto.
     *
     * @return int
     */
    public function obtenerValor() {
        return $this->valor;
    }

    /**
     * Devuelve un objeto que respresenta el colectivo donde se viajó.
     *
     * @return ColectivoInterface
     */
    public function obtenerColectivo() {
        return $this->colectivo;
    }

    public function obtenerFecha() {
        return $this->fecha;
    }

    public function obtenerTipoTarjeta() {
        return $this->tipoTarjeta;
    }

    public function obtenerSaldo() {
        return $this->saldo;
    }

    public function obtenerId() {
        return $this->id;
    }
}
