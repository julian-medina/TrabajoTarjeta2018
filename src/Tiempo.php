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
     * Devuelve la fecha actual, en segundos.
     *
     * @return int
     */
    public function time() {
        return time();
    }


    /**
    * Devuelve si una fecha actual es o no feriado.
    * 
    * @return bool
    *    TRUE si la fecha actual es un feriado o FALSE en su defecto.
    */
    public function feriado() {
        $fecha = date('d-m', $this->time());
        $feriados = $this->listaFeriado();
        return in_array($fecha, $feriados);
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
    * Agrega una fecha a la lista de feriados que hay registrados
    * 
    */
    public function agregarFeriado($fecha) {
        if (!in_array($fecha, $this->listaFeriado()))
            $this->listaFeriados[] = $fecha;
    }
    
    /**
    * Quita una fecha a la lista de feriados que hay registrados
    * 
    */
    public function eliminarFeriado($fecha) {
        $this->listaFeriados = array_diff($this->listaFeriado(), [$fecha]);
    }
}