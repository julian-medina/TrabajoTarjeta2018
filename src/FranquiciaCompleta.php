<?php

namespace TrabajoTarjeta;

/* agregamos aca implements TarjetaInterface ?? */
class FranquiciaCompleta extends Tarjeta implements TarjetaInterface {

  public function pagoBoleto($linea) {
    return TRUE;
  }
}
