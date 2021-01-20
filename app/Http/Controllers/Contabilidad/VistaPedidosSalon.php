<?php

namespace Donatella\Http\Controllers\Contabilidad;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class VistaPedidosSalon extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function index()
    {
        return view('contabilidad.reportesalonpedidos');

    }
}
