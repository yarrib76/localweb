<?php

namespace Donatella\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class VistaPedidosSalon extends Controller
{
    public function index()
    {
        return view('contabilidad.reportesalonpedidos');

    }
}
