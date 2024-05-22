<?php

namespace Donatella\Http\Controllers\Contabilidad;

use Donatella\Models\Estados_Financiera;
use Donatella\Models\FacturacionHist;
use Donatella\Models\Tipo_Pagos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Facturas extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }

    public function index()
    {
        return view('contabilidad.reportefacturasv2');
    }

    public function listarFacturas()
    {
        $facturas = DB::select ('SELECT CONCAT(clientes.nombre, ", ",clientes.apellido) as Cliente, NroFactura, ROUND(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE total END,2) as Totales,
                                    Envio, totalEnvio as TotalConEnvio, tipo_pagos.tipo_pago, estados.nombre, fecha, pagomixto,
                                    (SELECT
                                    CASE WHEN TotalConEnvio = 0 THEN ROUND(sum(Totales - (Totales * 2.5 / 100)),2)
                                        else ROUND(sum(TotalConEnvio - (TotalConEnvio * 2.5 / 100)),2) end) as Cobrar, comentario
                                    FROM samira.facturah
                                    inner join samira.clientes as clientes ON clientes.id_clientes = facturah.id_clientes
                                    inner join samira.tipo_pagos ON tipo_pagos.id_tipo_pagos = facturah.id_tipo_pago
                                    inner join samira.estados_financiera as estados ON estados.id_estado = id_estados_financiera
                                    order by NroFactura desc;');
        ob_start('ob_gzhandler');
        return Response::json($facturas);
    }

    public function tipo_pagos()
    {
        $tipoPagos = [];
        $tipoPagos = Tipo_Pagos::all();
        for ($i = 0; $i < $tipoPagos->count(); $i++ ){
            $arrTipePagos[$i] = [$tipoPagos[$i]->tipo_pago => $tipoPagos[$i]->tipo_pago];
        }
        ob_start('ob_gzhandler');
        return Response::json($arrTipePagos);
    }

    public function estados_financiera()
    {
        $tipoPagos = [];
        $estados_financiera = Estados_Financiera::where('id_estado','<>','2')->get();
        for ($i = 0; $i < $estados_financiera->count(); $i++ ){
            $arrEstadosFinanciera[$i] = [$estados_financiera[$i]->nombre => $estados_financiera[$i]->nombre];
        }
        ob_start('ob_gzhandler');
        return Response::json($arrEstadosFinanciera);
    }
    public function update()
    {
        $datos = Input::all();
        $id_tipo_pagos = DB::select('select id_tipo_pagos from samira.tipo_pagos
                                    where tipo_pago = "'.$datos['tipo_pago'].'"');
        $id_estados_financiera = DB::select ('select id_estado from samira.estados_financiera
                                    where nombre = "'.$datos['nombre'].'"');
        $articulo = FacturacionHist::where('NroFactura', $datos['NroFactura']);
        $articulo->update([
            'id_tipo_pago' => $id_tipo_pagos[0]->id_tipo_pagos,
            'id_estados_financiera' => $id_estados_financiera[0]->id_estado,
            'comentario' =>$datos['comentario'],
            'pagomixto' =>$datos['pagomixto']
        ]);
        return;
    }
}
