<?php

namespace Donatella\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function reporte()
    {
        return view('dashboard.reporte');
    }
}
