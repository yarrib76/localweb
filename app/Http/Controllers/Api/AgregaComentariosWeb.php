<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Models\ComentariosPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AgregaComentariosWeb extends Controller
{
    public function agregar()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        ComentariosPedidos::create([
            'controlpedidos_id' => Input::get('nroControlPedido'),
            'users_id' => Input::get('user_id'),
            'comentario' => Input::get('textarea'),
            'fecha' => $fecha
        ]);
        return Response::json('ok');
    }
}
