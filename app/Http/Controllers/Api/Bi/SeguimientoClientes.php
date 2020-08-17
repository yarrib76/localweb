<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Psy\Util\Json;

class SeguimientoClientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function query()
    {
        $cliente_id = Input::get('cliente_id');
        if (!empty($cliente_id)){
            $resultado = $this->consultaHistorica($cliente_id);
            return Response::json($resultado);
        }
        $añoInicio = Input::get('anioInicio');
        $añoFin = Input::get('anioFin');
        DB::statement("SET lc_time_names = 'es_ES'");
        $seguimiento = DB::select('SELECT cli.id_clientes as ID, CONCAT (cli.nombre, "," , cli.apellido) as Cliente
                                   FROM samira.clientes as cli
                                   LEFT JOIN samira.facturah as facth
                                   ON cli.id_clientes = facth.id_clientes
                                   and facth.fecha >= "' . $añoInicio .'" and facth.fecha <= "' . $añoFin .'"
                                   where cli.id_clientes <> 1 and facth.id_clientes is null;');

        if (empty($añoInicio)){
            return view('bi.seguimientoclientes', compact('seguimiento'));
        }
        return Response::json($seguimiento);
    }

    private function consultaHistorica($cliente_id)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $resultado = DB::select ('SELECT DATE_FORMAT(fecha, "%d %M %Y") as Fecha, NroFactura, Total FROM samira.facturah
                                  Where id_clientes = "'.$cliente_id.'"
                                  ORDER BY Fecha ASC;');
        return $resultado;
    }
}
