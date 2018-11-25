<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TiempoTest extends TestCase {

    public function testEliminoFeriado(){

        $tiempo = new TiempoFalso; //'01-01-70 01:00'

        
        $listaFeriados = array ( 
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
        $this->assertEquals($tiempo->listaFeriado(), $listaFeriados);
        $this->assertTrue(in_array("01-01", $tiempo->listaFeriado()));

        $tiempo->eliminarFeriado("01-01");
        $this->assertFalse(in_array("01-01", $tiempo->listaFeriado()));

        $tiempo->agregarFeriado("01-01");
        $this->assertTrue(in_array("01-01", $tiempo->listaFeriado()));
        
    }
    
}
