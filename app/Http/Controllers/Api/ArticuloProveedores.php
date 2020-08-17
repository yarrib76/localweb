<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;

class ArticuloProveedores extends Controller
{
    public function query()
    {
        $respuesta = '{"respuesta":["OK"]}';
        Artisan::call('reporte1:command');
        return Response::json($respuesta);
    }
}
