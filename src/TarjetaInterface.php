<?php

namespace TrabajoTarjeta;

interface TarjetaInterface {

    /**
     * Recarga una tarjeta con un cierto valor de dinero.
     *
     * @param float $monto
     *
     * @return bool
     *   Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
     *   sea valido.
     */
    public function recargar($monto);

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo();

    public function obtenerViajesPlus();

    public function pagarViajesPlus();

    /**
     * Devuelve la id de la tarjeta
     */
    public function obtenerId();

    /**
     * Paga un boleto y asigna $horaUltimoViaje y $ultimoColectivo
     * @param string $linea
     * 
     * @return bool
     * Devuelve TRUE si pudo pagar el boleto, o FALSE en caso de que no.
     */
    public function pagoBoleto($linea);

    /**
     * Devuelve el ultimoValorPagado
     */
    public function obtenerValorBoletoUtilizado();

    /**
     * Devuelve la cantidad de VP abonados.
     */
    public function obtenerViajesPlusAbonados();

    /**
     * Al usarse el numero de VP abonados, esta funcion lo vuelve a cero nuevamente.
     */
    public function reiniciarViajesPlusAbonados();

}
