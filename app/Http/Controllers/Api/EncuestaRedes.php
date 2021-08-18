<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class EncuestaRedes extends Controller
{
    public function consultaEncuesta()
    {
        $id_cliente = Input::get('id_cliente');
        $encuesta = DB::select('select encuesta from samira.clientes where id_clientes = "'.$id_cliente.'"');
        return Response::json($encuesta);
    }
    public function updateEncuesta()
    {
        $id_cliente = Input::get('id_cliente');
        $resultado = Input::get('encuesta');
        DB::select('update samira.clientes Set encuesta = "'.$resultado.'" where id_clientes = "'. $id_cliente .'";');
        return;
    }
}
