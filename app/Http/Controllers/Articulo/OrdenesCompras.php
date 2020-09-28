<?php

namespace Donatella\Http\Controllers\Articulo;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrdenesCompras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function query()
    {
        return view('articulos.ordenescomprasv2');
    }
}
