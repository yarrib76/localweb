<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Models\RegistroLlamadas;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AgregarRegistroLlamadas extends Controller
{
    public function agregar()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        RegistroLlamadas::create([
            'clientes_id' => Input::get('cliente_id'),
            'users_id' => Input::get('user_id'),
            'comentarios' => Input::get('textarea'),
            'fecha' => $fecha
        ]);
        return Response::json('ok');
    }
}
