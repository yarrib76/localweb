<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Ayuda\CodigoBarras;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Alta extends Controller
{
    public function nuevoArticulo()
    {
        $codigoBarra = new CodigoBarras();
        $codigo = $codigoBarra->crearDigitoCOntrol('779829000002');
        return $codigo;
    }
}
