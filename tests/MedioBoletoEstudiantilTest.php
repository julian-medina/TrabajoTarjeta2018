<?php
namespace TrabajoTarjeta;
use PHPUnit\Framework\TestCase;
class MedioBoletoEstudiantilTest extends TestCase {

     /* el monto del boleto pagado con medio boleto es siempre la mitad del normal. */
  
     public function testMedioBoletoEstudiantil() {
        $tiempo = new TiempoFalso();
        $tarjeta = new MedioBoletoEstudiantil($tiempo, "123456");
        $linea = 144;
        $empresa = "auckland"; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);
        $valor = 14.80;
        $boleto = new Boleto($valor, $colectivo, $tarjeta, NULL, NULL, NULL, NULL, NULL);
    
        $this->assertEquals(get_class($tarjeta),"TrabajoTarjeta\MedioBoletoEstudiantil");
        $this->assertEquals($tarjeta->obtenerSaldo(), 0);
        $this->assertTrue($tarjeta->recargar(20));
        $colectivo->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.8/2);
        $colectivo->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.8);
    
      }

      
    /* similar al test hecho con Tarjeta */
    public function testPagarConMedioBoletoEstudiantilSinSaldo() {
        $tiempo = new TiempoFalso();
        $tarjeta = new MedioBoletoEstudiantil($tiempo, "123456");
        $linea = "144 N";
        $empresa = 'Auckland'; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);
        $valor = $tarjeta->valorBoleto();
        $boletoPrimerPlus = new Boleto(0.0, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(), -1);
        $boletoUltimoPlus = new Boleto(0.0, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(), -2);
    

        $this->assertEquals($colectivo->pagarCon($tarjeta), $boletoPrimerPlus); //pagar sin saldo, el boleto es de un plus
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 1); //uso un viaje plus
        $this->assertEquals($boletoPrimerPlus->obtenerTipoBoleto(),"VIAJE PLUS"); //El tipo de boleto es el indicado

        $this->assertEquals($colectivo->pagarCon($tarjeta), $boletoUltimoPlus); //pagar sin saldo, el boleto es del ultimo plus
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 0); //usa otro viaje plus
        $this->assertEquals($boletoUltimoPlus->obtenerTipoBoleto(),"Ult. PLUS"); //El tipo de boleto es el indicado

        $this->assertFalse($colectivo->pagarCon($tarjeta)); //ya no quedan viajes plus

        /* el saldo es de 0, solo puede recargar 1 viaje plus */
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20-14.80);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 1);
        $this->assertEquals($tarjeta->obtenerViajesPlusAbonados(), 1);

        /* recargar los viajes plus */
        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerSaldo(), 20.4);
        $this->assertEquals($tarjeta->obtenerViajesPlus(), 2);
        $this->assertEquals($tarjeta->obtenerViajesPlusAbonados(), 2);

        $boletoAbonaDosPlus = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 2);
        $viajesPlusAbonados = 2;
        $valorPlus = $viajesPlusAbonados*$tarjeta->valorBoletoCompleto();
        $totalAbonado = $valorPlus + $tarjeta->valorBoleto();

        $this->assertEquals($colectivo->pagarCon($tarjeta), $boletoAbonaDosPlus); //pagar sin saldo, el boleto es del ultimo plus
        $this->assertEquals($boletoAbonaDosPlus->obtenerTipoBoleto(),"ABONA VIAJES PLUS: $29.6\nTOTAL ABONADO: $37"); //El tipo de boleto es el indicado
        $this->assertEquals($boletoAbonaDosPlus->obtenerTipoBoleto(),"ABONA VIAJES PLUS: $".$valorPlus."\nTOTAL ABONADO: $".$totalAbonado); //El tipo de boleto es el indicado

    }

    public function testMedioBoletoEstudiantilCada5Minutos() {
        $tiempo = new TiempoFalso();
        $tarjeta = new MedioBoletoEstudiantil($tiempo, "123456");
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
        $boleto = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo()-$valor, $tarjeta->obtenerId(), 0);
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boleto);
    }


}
