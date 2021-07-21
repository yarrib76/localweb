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
        $clientes = DB::select('select id_clientes, clientes.nombre, apellido, apodo, cuit, direccion, mail, telefono,localidad, provincias.nombre as provincia, created_at as fecha, id_provincia from samira.clientes
                                    inner join samira.provincias ON provincias.id = clientes.id_provincia
                                    where id_clientes <> 1 Order by clientes.nombre desc');
        ob_start('ob_gzhandler');
        return Response::json($clientes);
    }

}
