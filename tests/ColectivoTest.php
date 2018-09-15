<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

/* Comprueba que se puede pagar si la tarjeta tiene saldo */

    public function testPagarConTarjeta() {
        $tiempo = new TiempoFalso();
        $tarjeta = new Tarjeta($tiempo, "123456");
        $linea = "144 N";
        $empresa = "auckland"; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);
        $valor = 14.80;
        $boleto = new Boleto($valor, $colectivo, $tarjeta, NULL, NULL, NULL, NULL, NULL);

        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20.0);

        $this->assertEquals($colectivo->pagarCon($tarjeta)->obtenerColectivo(), $boleto->obtenerColectivo());
        /* Comprueba que se descuenta el dinero al pagar */
        $this->assertEquals($tarjeta->obtenerSaldo(), 20.0-$valor);

        $this->assertEquals($tarjeta->obtenerViajesPlus(), 2);
    }

    /* Comprueba que NO se puede pagar si la tarjeta no tiene saldo */
    /* Valida que se pueden dar hasta 2 viajes plus */
    public function testPagarConTarjetaSinSaldo() {
        $tiempo = new TiempoFalso();
        $tarjeta = new Tarjeta($tiempo, "123456");
        $linea = "144 N";
        $empresa = 'Auckland'; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);

        $valor = 14.80;
        $boleto = new Boleto($valor, $colectivo, $tarjeta, NULL, NULL, NULL, NULL, NULL);

        $colectivo->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 1);

        $colectivo->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 0);

        $colectivo->pagarCon($tarjeta);
        $this->assertFalse($colectivo->pagarCon($tarjeta));

        /* el saldo es de 0, solo puede recargar 1 viaje plus */
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.80);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 1);

        /* recargar los viajes plus */
        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.80*2+30);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 2);
        
    }
}
