<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GetTransportes extends Controller
{
    public function listaTransportes()
    {
        $datos = DB::select("select nombre from samira.transportes;");
        return Response::json($datos);
    }
}
