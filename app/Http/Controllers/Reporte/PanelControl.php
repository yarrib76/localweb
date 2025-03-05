<?php

namespace Donatella\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class PanelControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function panel()
    {
        return view('dashboard.panel');
    }
}
