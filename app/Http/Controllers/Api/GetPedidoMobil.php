<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class GetPedidoMobil extends Controller
{
    public function query()
    {
        $nroPedido = Input::get('nroPedido');
        $pedido = DB::select('SELECT nroPedido, Articulo, Detalle, Cantidad,PrecioArgen, PrecioUnitario,PrecioVenta,
                              Vendedora, Ganancia
                              FROM samira.pedidotemp
                              WHERE nropedido = "'. $nroPedido .'";');
        return Response::json($pedido);
    }

}
