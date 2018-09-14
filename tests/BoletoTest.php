<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testSaldoCero() {
        $valor = 14.80;
        $boleto = new Boleto($valor, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

        $this->assertEquals($boleto->obtenerValor(), $valor);
    }
}
