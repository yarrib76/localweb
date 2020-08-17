<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class TransportePedido extends Controller
{
    public function modificarTransporte()
    {
        $nroPedido = Input::get('nropedido');
        $transporte = Input::get('transporte');
        DB::select('update samira.controlpedidos Set transporte = "'. $transporte .'" where nropedido = "'. $nroPedido .'";');
        return Response::json ($transporte);
    }

}
