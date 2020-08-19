<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EncuestaRedes extends Controller
{
    public function updateEncuesta()
    {
        $nroPedido = Input::get('nropedido');
        $resultado = Input::get('resultado');
        DB::select('update samira.controlpedidos Set encuesta = "'. $resultado .'" where nropedido = "'. $nroPedido .'";');
        return;
    }
}
