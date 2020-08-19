<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class PedidoEnviado extends Controller
{
    public function enviado()
    {
        $nroPedido = Input::get('nroPedido');
        DB::select('update samira.controlpedidos Set empaquetado = 2 where nropedido = "'. $nroPedido .'";');
        $respuesta = '{"respuesta":["OK"]}';
        return Response::json($respuesta);
    }
}
