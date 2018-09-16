<?php
namespace TrabajoTarjeta;
use PHPUnit\Framework\TestCase;
class MedioBoletoUniversitarioTest extends TestCase {

    public function testPagarConMedioBoletoUniversitario(){
        $tiempo = new TiempoFalso;
        $id = "123456";
        $tarjeta = new MedioBoletoUniversitario($tiempo, $id);
        $tarjeta->recargar(20); //con 20 alcanza para el viaje
        $valor = 14.80;
        $colectivo = new Colectivo(NULL, NULL, NULL);
      
        $boleto = new Boleto(($valor/2), $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - ($valor/2), $tarjeta->obtenerId(), 0);
        $tiempo->avanzar(300);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
        
    }

}