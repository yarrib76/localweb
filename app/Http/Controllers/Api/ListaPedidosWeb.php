<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\PedidosTemp;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ListaPedidosWeb extends Controller
{
    public function query()
    {
        $nroPedido = Input::get('nroPedido');
        $pedidos = PedidosTemp::where('NroPedido', '=', $nroPedido)->get();
        return Response::json($pedidos);
    }
}
