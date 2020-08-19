<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Donatella\Models\Clientes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class DatosClientes extends Controller
{
    public function query()
    {
        $cliente_id = Input::get('cliente_id');
        $datosClientes = Clientes::where('id_clientes', '=', $cliente_id)->get()->load('provincias');
        return Response::json($datosClientes);
    }
}
