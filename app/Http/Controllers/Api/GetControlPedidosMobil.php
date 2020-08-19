<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GetControlPedidosMobil extends Controller
{
    public function query()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $data = DB::select('SELECT control.nropedido, CONCAT (cli.nombre, "," , cli.apellido) as Cliente, control.ordenWeb, control.total,
                            DATE_FORMAT(control.fecha, "%d de %M %Y") AS fecha
                            FROM samira.controlpedidos as control
                            inner join samira.clientes cli ON control.id_cliente = cli.id_clientes
                            WHERE estado = 1
                            ORDER BY control.nropedido DESC;');
        return Response::json($data);
    }
}
