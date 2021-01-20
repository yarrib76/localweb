<?php

namespace Donatella\Http\Controllers\Api\Reportes;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class PedidosSalon extends Controller
{

    public function ventasSalonFacturado()
    {
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        DB::select('SET lc_time_names = es_ES');
        $consulta = DB::select('select ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE facth.total END),2) as Total, upper(date_format(Facth.fecha, "%Y-%m-%d")) as Fecha from samira.facturah as Facth
                                    left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and (Control.nrofactura is null
                                    or Control.ordenWeb is null
                                    or Control.ordenWeb = 0)
                                    group by Facth.fecha;');
        return Response::json($consulta);
    }

    public function  ventasSalonCantidad()
    {
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $consulta = DB::select('select count(*) as cantidad from samira.facturah as Facth
                                    left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and (Control.nrofactura is null
                                    or Control.ordenWeb is null
                                    or Control.ordenWeb = 0);');
        return Response::json($consulta);
    }

    public function ventasPedidosFacturados()
    {
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $consulta = DB::select('select ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE facth.total END),2) as Total, upper(date_format(Facth.fecha, "%Y-%m-%d")) as Fecha from samira.facturah as Facth
                                    inner join samira.controlpedidos as control ON control.nrofactura = Facth.nrofactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and Control.ordenWeb is Not null
                                    and Control.ordenWeb <> 0
                                    group by Facth.fecha;');
        return Response::json($consulta);
    }

    public function ventasPedidosCantidad()
    {
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $consulta = DB::select('select count(*) as cantidad from samira.facturah as Facth
                                    left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and Control.ordenWeb is Not null
                                    and Control.ordenWeb <> 0;');
        return Response::json($consulta);
    }
}
