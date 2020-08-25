<?php

namespace Donatella\Http\Controllers\Api\OC;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrdenCompras extends Controller
{
    public function consulta()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $datos = DB::select('SELECT OrdenCompra, Articulo, Detalle, Cantidad, DATE_FORMAT(FechaCompra, "%d de %M %Y") as FechaCompra,
                                FechaCompra as fechaParaOrden,
                                CASE
                                WHEN (TipoOrden = 2 and Cantidad <> 0) THEN "Ingreso"
                                WHEN (TipoOrden = 1 and Cantidad <> 0) THEN "Egreso"
                                WHEN cantidad = 0 THEN "Modificacion"
                                END as TipoOrden, Observaciones
                                FROM samira.compras
                                where TipoOrden IS NOT NULL;');
        return Response::json($datos);
    }
}
