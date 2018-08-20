<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tarjeta = new Tarjeta;

        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);
        
        $this->assertTrue($tarjeta->recargar(510.15));
        $this->assertEquals($tarjeta->obtenerSaldo(), 510.15+81.93+10);
        
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 510.15+81.93+30);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
      $tarjeta = new Tarjeta;

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

    /* FranquiciaCompleta siempre puede pagar un boleto */
  public function testFranquiciaCompletaSiemprePuedePagar() {
    $tarjeta = new FranquiciaCompleta;
    $colectivo = new Colectivo;
    $valor = 14.80;
    $boleto = new Boleto($valor, $colectivo, $tarjeta);

    $this->assertEquals($tarjeta->obtenerSaldo(), 99);
    $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);
    $this->assertEquals($tarjeta->obtenerSaldo(), 99);
  }
    /* el monto del boleto pagado con medio boleto es siempre la mitad del normal. */
  public function testMedioBoleto() {
    $tarjeta = new MedioBoleto;
    $colectivo = new Colectivo;
    $valor = 14.80;
    $boleto = new Boleto($valor, $colectivo, $tarjeta);

    $this->assertEquals(get_class($tarjeta),"TrabajoTarjeta\MedioBoleto");
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertTrue($tarjeta->recargar(20));
    $colectivo->pagarCon($tarjeta);
    $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.8/2);
    $colectivo->pagarCon($tarjeta);
    $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.8);

  }

}
