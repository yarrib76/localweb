<?php

namespace Donatella\Http\Controllers\CierreDiario;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Psy\Util\Json;

class ControlCierreDiario extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function index()
    {
        return view('cierrediario.controlcierre');
    }

    public function cierreCaja()
    {
        $fecha = Input::get('fecha');
        $query = DB::Select('SELECT
                              CASE
                                WHEN tipo_pago = "Efectivo" THEN "billete.jpeg"
                                WHEN tipo_pago = "TransferenciaBco" THEN "bancos.jpeg"
                                WHEN tipo_pago = "MercadoPago" THEN "mercadopago.png"
                              END as tipo_pago,
                              count(*) as cantidad, ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE total END),2) as Total FROM samira.facturah
                              inner join samira.tipo_pagos ON tipo_pagos.id_tipo_pagos = facturah.id_tipo_pago
                              where fecha = "'.$fecha.'" and id_tipo_pagos > 1
                              group by tipo_pago;');
        return Response::json($query);
    }
}
