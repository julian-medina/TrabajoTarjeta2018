<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

/* Comprueba que se puede pagar si la tarjeta tiene saldo */

    public function testPagarConTarjeta() {
        $tarjeta = new Tarjeta;
        $colectivo = new Colectivo;
        $valor = 14.80;
        $boleto = new Boleto($valor, $colectivo, $tarjeta);

        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20.0);

        $this->assertEquals($colectivo->pagarCon($tarjeta)->obtenerColectivo(), $boleto->obtenerColectivo());
        /* Comprueba que se descuenta el dinero al pagar */
        $this->assertEquals($tarjeta->obtenerSaldo(), 20.0-$valor);
    }

    /* Comprueba que NO se puede pagar si la tarjeta no tiene saldo */

    public function testPagarConTarjetaSinSaldo() {
        $tarjeta = new Tarjeta;
        $colectivo = new Colectivo;
        $valor = 14.80;
        $boleto = new Boleto($valor, $colectivo, $tarjeta);

        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }
}
