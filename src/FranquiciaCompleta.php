<?php

namespace TrabajoTarjeta;

class FranquiciaCompleta extends Tarjeta implements TarjetaInterface {

  public function pagoBoleto($linea) {
    return TRUE;
  }
}
