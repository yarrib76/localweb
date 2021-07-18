<?php

namespace Donatella\Http\Controllers\Api\Clientes;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GetClientes extends Controller
{
    public function consulta()
    {
        $clientes = DB::select('select * from samira.clientes
                                where id_clientes <> 1 Order by nombre DESC');
        ob_start('ob_gzhandler');
        return Response::json($clientes);
    }

}
