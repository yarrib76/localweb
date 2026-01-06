<?php

namespace Donatella\Http\Controllers\Articulo;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ImportSincro extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function index()
    {
        return view('sincroarticulos.reporte');
    }

}

