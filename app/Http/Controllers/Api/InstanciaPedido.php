<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class InstanciaPedido extends Controller
{
    public function modificoInstanciaPedido()
    {
        $nroPedido = Input::get('nroPedido');
        $instancia = Input::get('instancia');
        $fechaHora = date("Y-m-d H:i:s");
        if ($instancia == 1){
            DB::select('update samira.controlpedidos Set instancia ="'.$instancia.'", fecha_inicio_instancia ="'.$fechaHora.'"  where nropedido = "'. $nroPedido .'";');
            $instanciaTexto = 'Iniciado';
        }else {
            DB::select('update samira.controlpedidos Set instancia ="'.$instancia.'", fecha_fin_instancia ="'.$fechaHora.'"  where nropedido = "'. $nroPedido .'";');
            $instanciaTexto =  'Finalizado';
        }

        return Response::json($instanciaTexto);
    }
}
