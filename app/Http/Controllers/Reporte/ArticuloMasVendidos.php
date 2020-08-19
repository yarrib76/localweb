<?php

namespace Donatella\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class ArticuloMasVendidos extends Controller
{
    public function reporte()
    {
        return view('reporte.artimasvendidos');
    }
}
