<?php

namespace TrabajoTarjeta;

interface TiempoInterface {
    
    public function time();

    /**
    * Devuelve si una fecha actual es o no feriado.
    * 
    * @return bool
    *    TRUE si la fecha actual es un feriado o FALSE en su defecto.
    */
    public function feriado();

    /**
    * Devuelve la lista de feriados que hay registrados
    * 
    * @return array
    *    Una lista con todos los feriados
    */
    public function listaFeriado();

    /**
    * Agrega una fecha a la lista de feriados que hay registrados
    */
      public function agregarFeriado($fecha);

    /**
    * Quita una fecha a la lista de feriados que hay registrados
    */
    public function eliminarFeriado($fecha);
}