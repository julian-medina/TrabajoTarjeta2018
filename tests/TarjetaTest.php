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

        $tarjeta2 = new Tarjeta($tiempo, "123456");
        $this->assertTrue($tarjeta2->recargar(962.59));
        $this->assertEquals($tarjeta2->obtenerSaldo(), 962.59 + 221.58);
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

  public function testPagarTrasbordoLunesaViernes(){
    $tiempo = new TiempoFalso();
    $valor = 14.80;
    $pasar_dia = 24*60*60; //24 horas * 60 minutos * 60 segundos = segundos en 1 dia
    $id = rand(69,420);
    $tarjeta = new Tarjeta($tiempo, $id);
    $linea = "K";
    $empresa = "Semtur";
    $numero = "5";
    $colectivo1 = new Colectivo($linea, $empresa, $numero);
    $this->assertTrue($tarjeta->recargar(10)); //debe devolver true ya que es monto permitido
    $this->assertEquals($tarjeta->obtenerSaldo(), 10);//saldo debe ser 10
    $this->assertTrue($tarjeta->recargar(20));//debe devolver true ya que es monto permitido
    $this->assertEquals($tarjeta->obtenerSaldo(), 30);//saldo debe ser 10+20 = 30
    $boleto = new Boleto($valor, $colectivo1, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
    $this->assertEquals($colectivo1->pagarCon($tarjeta), $boleto); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 15.2);
    $linea = "116";
    $empresa = "Semtur";
    $numero = "7";
    $colectivo2 = new Colectivo($linea, $empresa, $numero);
    $valorT = ($valor*33)/100;

    $this->assertNotEquals(time(), 0); // tiempo real no empieza en 0
    $this->assertEquals($tiempo->time(), 0); // tiempo falso empieza en 0
    $tiempo->avanzar(300); //pasan 5 minutos trasbordo es permitido
    $boleto = $colectivo2->pagarCon($tarjeta);
    
    $this->assertEquals("TRASBORDO", $boleto->obtenerTipoBoleto()); // pago un trasbordo
    
    $this->assertEquals($tarjeta->obtenerSaldo(), 10.316); // si pago trasbordo me queda 10.316 de saldo
    $this->assertTrue($tarjeta->recargar(20));//debe devolver true ya que es monto permitido
    $this->assertEquals($tarjeta->obtenerSaldo(), 30.316);
    $boleto = $colectivo1->pagarCon($tarjeta);
    $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 15.516);
    $tiempo->avanzar(5401); //me voy del tiempo limite de pagar trasbordo
    $boleto = new Boleto($valor, $colectivo2, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), "que es esto",0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
    $this->assertEquals($colectivo2->pagarCon($tarjeta),$boleto); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 0.716);
    
    $tiempo->avanzar(60*60*5); //avanzo 5 horas para asegurarme de estar fuera del periodo entre 22 a 6 ya que empecé un jueves a las 00
    $this->assertTrue($tarjeta->recargar(962.59));//debe devolver true ya que es monto permitido
    $this->assertEquals($tarjeta->obtenerSaldo(), 1184.886);
    $boleto = new Boleto($valor, $colectivo1, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
    $this->assertEquals($colectivo1->pagarCon($tarjeta),$boleto); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 1170.086);
    $boleto = $colectivo2->pagarCon($tarjeta);
    $this->assertEquals("TRASBORDO",$boleto->obtenerTipoBoleto()); // pago un viaje trasbordo
    $this->assertEquals($tarjeta->obtenerSaldo(), 1165.202);
    $boleto = $colectivo1->pagarCon($tarjeta);
    $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 1150.402);
    //ahora tendria que dejarme pagar trasbordo pero voy a adelantar el tiempo superando el limite
    $tiempo->avanzar(60*90);
    $boleto = new Boleto($valor, $colectivo2, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
    
    /* revisar estos tests */
    $this->assertEquals($colectivo2->pagarCon($tarjeta),$boleto); // pago un viaje normal
    $this->assertEquals($tarjeta->obtenerSaldo(), 1135.602);
    //ya verifique los dos horarios posibles dentro de los dias de lunes a viernes así que como para el resto de dias de lunes a viernes sería lo mismo corto el test aca
    
}

public function testPagarTrasbordoSabado(){
  $tiempo = new TiempoFalso();
  $valor = 14.80;
  $valorT = ($valor*33)/100;
  $pasar_dia = 24*60*60; //24 horas * 60 minutos * 60 segundos = segundos en 1 dia
  $id = rand(69,420);
  $tarjeta = new Tarjeta($tiempo, $id);
  $linea = "K";
  $empresa = "Semtur";
  $numero = "5";
  $colectivo1 = new Colectivo($linea, $empresa, $numero);
  $linea = "116";
  $empresa = "Semtur";
  $numero = "7";
  $colectivo2 = new Colectivo($linea, $empresa, $numero);
  $this->assertTrue($tarjeta->recargar(962.59)); //debe devolver true ya que es monto permitido
  $this->assertEquals($tarjeta->obtenerSaldo(), 1184.17);//saldo debe ser 1184.17
  $tiempo->avanzar($pasar_dia * 2); // paso 2 dias ya que empieza en jueves y necesito ir al sabado, ahora estaría en sabado a las 00
  //como ya sabemos como funciona en este horario el trasbordo por el test anterior (ya que de 22 a 6 es igual para todo dia) adelantamos a otro horario
  $tiempo->avanzar(60*60*7); //adelantamos unas 7 horas para posicionarnos en el rango de 6 a 14hs
  $boleto = new Boleto($valor, $colectivo1, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
  $this->assertEquals($colectivo1->pagarCon($tarjeta),$boleto); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1169.37);
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("TRASBORDO", $boleto->obtenerTipoBoleto()); // pago un viaje trasbordo
  $this->assertEquals($tarjeta->obtenerSaldo(), 1164.486);
  $boleto = $colectivo1->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1149.686);
  $tiempo->avanzar(3601); //me voy del tiempo para pagar trasbordo los dias sabados de 6 a 14
  $boleto = new Boleto($valor, $colectivo2, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
  $this->assertEquals($colectivo2->pagarCon($tarjeta),$boleto); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1134.886);
  //ahora paso al otro horario, de 14 a 22
  $tiempo->avanzar(60*60*6); //son las 8 asi que adelanto 6 horas para que sean las 14
  $boleto = new Boleto($valor, $colectivo1, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
  $this->assertEquals($colectivo1->pagarCon($tarjeta),$boleto); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1120.086);
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("TRASBORDO",$boleto->obtenerTipoBoleto()); // pago un viaje trasbordo
  $this->assertEquals($tarjeta->obtenerSaldo(), 1115.202);
  $boleto = $colectivo1->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1100.402);
  $tiempo->avanzar(5401);//me salgo del tiempo permitido trasbordo
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1085.602);
}

public function testPagarTrasbordoDomingo(){
  $tiempo = new TiempoFalso();
  $valor = 14.80;
  $valorT = ($valor*33)/100;
  $pasar_dia = 24*60*60; //24 horas * 60 minutos * 60 segundos = segundos en 1 dia
  $id = rand(69,420);
  $tarjeta = new Tarjeta($tiempo, $id);
  $linea = "K";
  $empresa = "Semtur";
  $numero = "5";
  $colectivo1 = new Colectivo($linea, $empresa, $numero);
  $linea = "116";
  $empresa = "Semtur";
  $numero = "7";
  $colectivo2 = new Colectivo($linea, $empresa, $numero);
  $this->assertTrue($tarjeta->recargar(962.59)); //debe devolver true ya que es monto permitido
  $this->assertEquals($tarjeta->obtenerSaldo(), 1184.17);//saldo debe ser 1184.17
  $tiempo->avanzar($pasar_dia * 3); // paso 3 dias ya que empieza en jueves y necesito ir al domingo, ahora estaría en domingo a las 00
  //si bine ya sabemos como funciona en este horario el trasbordo por el test anterior (ya que de 22 a 6 es igual para todo dia) vamos a correr un par de pruebas
  $boleto = new Boleto($valor, $colectivo1, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
  $this->assertEquals($colectivo1->pagarCon($tarjeta),$boleto); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1169.37);
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("TRASBORDO",$boleto->obtenerTipoBoleto()); // pago un viaje trasbordo
  $this->assertEquals($tarjeta->obtenerSaldo(), 1164.486);
  $boleto = $colectivo1->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1149.686);
  $tiempo->avanzar(5401); //me voy del tiempo para pagar trasbordo los dias domingos de 22 a 6
  $boleto = new Boleto($valor, $colectivo2, $tarjeta, date("d/m/y H:i", time()), get_class($tarjeta), $tarjeta->obtenerSaldo() - $valor, $tarjeta->obtenerId(), 0, 0); //boleto a comparar mas adelante ya que pagarCon devuelve FALSE | Boleto
  $this->assertEquals($colectivo2->pagarCon($tarjeta),$boleto); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1134.886);
  $tiempo->avanzar(60*60*6); //adelantamos unas 6 horas para posicionarnos en el rango de 6 a 22hs
  $boleto = $colectivo1->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1120.086);
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("TRASBORDO",$boleto->obtenerTipoBoleto()); // pago un viaje trasbordo
  $this->assertEquals($tarjeta->obtenerSaldo(), 1115.202);
  $boleto = $colectivo1->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1100.402);
  $tiempo->avanzar(5401); //me voy del tiempo para pagar trasbordo los dias domingos de 6 a 22
  $boleto = $colectivo2->pagarCon($tarjeta);
  $this->assertEquals("NORMAL",$boleto->obtenerTipoBoleto()); // pago un viaje normal
  $this->assertEquals($tarjeta->obtenerSaldo(), 1085.602);
}
}
