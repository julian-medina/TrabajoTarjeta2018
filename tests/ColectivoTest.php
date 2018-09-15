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

        //test de las funciones en clase colectivo
        $this->assertEquals($colectivo->linea(), $linea);
        $this->assertEquals($colectivo->empresa(), $empresa);
        $this->assertEquals($colectivo->numero(), $numero);
    }

    /* Comprueba que NO se puede pagar si la tarjeta no tiene saldo */
    /* Valida que se pueden dar hasta 2 viajes plus */
    /* Comprueba que devuelve el tipo de boleto correcto (plus abonados, usado, o viaje normal) */
    public function testPagarConTarjetaSinSaldo() {
        $tiempo = new TiempoFalso();
        $tarjeta = new Tarjeta($tiempo, "123456");
        $linea = "144 N";
        $empresa = 'Auckland'; 
        $numero = 2;
        $colectivo = new Colectivo($linea, $empresa, $numero);
        $valor = $tarjeta->valorBoleto();
        $boletoPrimerPlus = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(), -1);
        $boletoUltimoPlus = new Boleto($valor, $colectivo, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo(), $tarjeta->obtenerId(), -2);
    

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
        $this->assertEquals($colectivo->pagarCon($tarjeta), $boletoAbonaDosPlus); //pagar sin saldo, el boleto es del ultimo plus
        $this->assertEquals($boletoAbonaDosPlus->obtenerTipoBoleto(),"ABONA VIAJES PLUS: ".$viajesPlusAbonados*$tarjeta->valorBoletoCompleto()); //El tipo de boleto es el indicado
        
    }
}
