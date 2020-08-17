<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\ControlPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class cancelarPedido extends Controller
{
    public function cancelar()
    {
        $datos = Input::all();
        $pedido = ControlPedidos::where('nroPedido', $datos['nroPedido'])->get();
        $pedido[0]->update([
            'Estado' => '2'
        ]);
        $respuesta = '{"respuesta":["OK"]}';
        return Response::json($respuesta);
    }
}
