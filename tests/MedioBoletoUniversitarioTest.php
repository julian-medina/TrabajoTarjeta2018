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
        $tiempo->avanzar(300);
        $boleto = new Boleto(($valor/2), $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - ($valor/2), $tarjeta->obtenerId(), 0);
        $tiempo->avanzar(300);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
        
    }

    public function testMedioBoletoUniversitarioCada5Minutos() {
        $tiempo = new TiempoFalso();
        $tarjeta = new MedioBoletoUniversitario($tiempo, "123456");
        $linea = "144 N";
        $empresa = 'Auckland'; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);
        $valor = $tarjeta->valorBoleto();
        $tiempo->avanzar(300); //avanzo el tiempo al principio para que no se guarde NULL (0) en la ult fecha pagada 
        $tarjeta->recargar(100);
        $boleto = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 0);
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);

        $this->assertNotEquals($tiempo->time(), NULL);
        /* no se puede pagar si no pasaron 5 minutos */
        $this->assertFalse($tarjeta->tiempoDeEsperaCumplido());
        $this->assertFalse($colectivo->pagarCon($tarjeta));

        /* pasaron 5 minutos y se puede pagar */
        $tiempo->avanzar(300);
        $this->assertTrue($tarjeta->medioDisponible());
        $boleto = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 0);
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);

        /* acomo ya se usaron los 2 viajes con medio Boleto diarios, el valor del boleto es el completo. */
        $tiempo->avanzar(300);
        $boleto = new Boleto($valor*2, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor*2, $tarjeta->obtenerId(), 0);
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);
        $this->assertFalse($tarjeta->medioDisponible());

        /* ya paso un dia, asi que nuevamente se dispone de los dos medios boletos. */
        $tiempo->avanzar(86400); //86400 segundos es un dia.
        $boleto = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 0);
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);
        $this->assertTrue($tarjeta->medioDisponible());
        
    }


}