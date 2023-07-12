<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class EstadoPagoPedido extends Controller
{
    public function modificoEstadoPago()
    {
        $nroPedido = Input::get('nroPedido');
        $estado = Input::get('estado');
        $noPagado = 0;
        $pagado = 1;

        if ($estado == 0){
            DB::select('update samira.controlpedidos set pagado = "'.$pagado.'" where nropedido = "'.$nroPedido.'"');
            //Indica que se definio el pagado como 1 (pedido pago)
            $estadoControl = 1;
        }else {
            DB::select('update samira.controlpedidos set pagado = "'.$noPagado.'" where nropedido = "'.$nroPedido.'"');
            //Indica que se definio el pagado como 0 (pedido No pagado)
            $estadoControl = 0;
        }
        return Response::json($estadoControl);
    }
}
