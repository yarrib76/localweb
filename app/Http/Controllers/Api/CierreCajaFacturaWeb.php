<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CierreCajaFacturaWeb extends Controller
{
    public function query()
    {
        $nroFactura = Input::get('nroFactura');
        $cierresDiarios = DB::select('SELECT NroFactura, Articulo, Detalle, Cantidad, ROUND(PrecioArgen,2) as PrecioArgen, ROUND(PrecioUnitario,2) as PrecioUnitario, ROUND(PrecioVenta,2) as PrecioVenta, ROUND(Ganancia,2) as Ganancia, Cajera, Vendedora FROM samira.factura
                                      where NroFactura = "' .$nroFactura. '"');
        return Response::json($cierresDiarios);
    }
}
