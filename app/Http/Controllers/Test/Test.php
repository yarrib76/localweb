<?php

namespace Donatella\Http\Controllers\Test;

use Carbon\Carbon;
use Donatella\Ayuda\GetPuntos;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Test extends Controller
{
    public function Test()
    {
        $puntos = new GetPuntos();
        dd($puntos->calcularPuntos(1841,'25670.20'));
    }
}
