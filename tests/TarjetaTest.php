<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tiempo = new TiempoFalso();
        $tarjeta = new Tarjeta($tiempo, "123456");

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
      $tiempo = new TiempoFalso();
      $tarjeta = new Tarjeta($tiempo, "123456");

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

    /* FranquiciaCompleta siempre puede pagar un boleto */
  public function testFranquiciaCompletaSiemprePuedePagar() {
    $tiempo = new TiempoFalso();
    $id = "123456";
    $tarjeta = new FranquiciaCompleta($tiempo, $id);

    $colectivo = new Colectivo(NULL, NULL, NULL);
        
    $boleto = new Boleto(0, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(), 0);
    
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

  public function testPagarConTiemopReal() {
    $tiempo = new Tiempo();
    $tarjeta = new MedioBoletoEstudiantil($tiempo, "123456");
    $linea = "144 N";
    $empresa = 'Auckland'; 
    $numero = 2;
    $colectivo = new Colectivo($linea, $empresa, $numero);
    $valor = $tarjeta->valorBoleto();

    $tarjeta->recargar(100);
    $boleto = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 0);
    $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);

    $this->assertNotEquals($tiempo->time(), NULL);
    
    /* no se puede pagar si no pasaron 5 minutos */
    $this->assertFalse($tarjeta->tiempoDeEsperaCumplido());
    $this->assertFalse($colectivo->pagarCon($tarjeta));
  }
}
