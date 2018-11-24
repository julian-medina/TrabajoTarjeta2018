<?php

namespace TrabajoTarjeta;

class Tiempo implements TiempoInterface {

    public $listaFeriados = array ( 
        '01-01', //  Año Nuevo
        '24-03', //  Día Nacional de la Memoria por la Verdad y la Justicia.
        '02-04', //  Día del Veterano y de los Caídos en la Guerra de Malvinas.
        '01-05', //  Día del trabajador.
        '25-05', //  Día de la Revolución de Mayo. 
        '17-06', //  Día Paso a la Inmortalidad del General Martín Miguel de Güemes.
        '20-06', //  Día Paso a la Inmortalidad del General Manuel Belgrano. F
        '09-07', //  Día de la Independencia.
        '17-08', //  Paso a la Inmortalidad del Gral. José de San Martín
        '12-10', //  Día del Respeto a la Diversidad Cultural 
        '20-11', //  Día de la Soberanía Nacional
        '08-12', //  Inmaculada Concepción de María
        '25-12', //  Navidad
    );
    
    /**
     * Devuelve el dia actual, en segundos.
     *
     * @return int
     */
    public function time() {
        return time();
    }


    /**
    * Devuelve si el dia actual es o no feriado.
    * 
    * @return bool
    *    TRUE si el dia actual es un feriado o FALSE en su defecto.
    */
    public function feriado() {
        $dia = date('d-m', $this->time());
        $feriados = $this->listaFeriado();
        return in_array($dia, $feriados);
    }

    /**
    * Devuelve la lista de feriados que hay registrados
    * 
    * @return array
    *    Una lista con todos los feriados
    */
    public function listaFeriado() {
        return $this->listaFeriados;
    }

    /**
    * Agrega el dia a la lista de feriados que hay registrados
    * 
    */
    public function agregarFeriado($dia) {
        if (!in_array($dia, $this->listaFeriado()))
            $this->listaFeriados[] = $dia;
    }

    /**
    * Quita el dia a la lista de feriados que hay registrados
    * 
    */
    public function eliminarFeriado($dia) {
        $this->listaFeriados = array_diff($this->listaFeriado(), [$dia]);
    }
}