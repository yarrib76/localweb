<?php

namespace Donatella\Http\Controllers\Reporte\DashBoard;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Consultas extends Controller
{
    public function consultaEmpaquetados()
    {
        $fecha_actual = date("Y-m-d");
        $fecha_limite = (date("Y-m-d",strtotime($fecha_actual."- 3 days")));
        $empaquetadosTotal = DB::select('SELECT count(*) as Total,
                                    if ("'.$fecha_limite.'" <= facturah.fecha, 1, 0) as suma
                                    from samira.controlPedidos as pedidos
                                    INNER JOIN samira.facturah as facturah ON facturah.NroFactura = pedidos.nrofactura
                                    where pedidos.estado = 0 and pedidos.empaquetado = 1
                                    group by suma
                                    having suma = 0;');
        $empaquetadosPendientes = DB::select('select count(*) as Pendientes from samira.controlPedidos as pedidos
                                            INNER JOIN samira.facturah as facturah ON facturah.NroFactura = pedidos.nrofactura
                                            where pedidos.estado = 0 and pedidos.empaquetado = 1');

        $empaquetadosSinTransporte = DB::select('select count(*) as SinTransporte from samira.controlPedidos as pedidos
                                            INNER JOIN samira.facturah as facturah ON facturah.NroFactura = pedidos.nrofactura
                                            where pedidos.estado = 0 and pedidos.empaquetado = 1 and (transporte = "" or transporte is null);');
        if (!empty($empaquetadosTotal[0]->Total)){
            $empaquetados['empaquetadosVencidos']=$empaquetadosTotal[0]->Total;
        } else $empaquetados['empaquetadosVencidos'] = 0;

        $empaquetados['empaquetadosPendientes']=$empaquetadosPendientes[0]->Pendientes;
        $empaquetados['empaquetadosSinTransporte']=$empaquetadosSinTransporte[0]->SinTransporte;
        return Response::json($empaquetados);
    }

    public function carritosAbandanados()
    {
        $carritosAgandonadosSinAsignar = DB::select ('SELECT count(*) as SinAsignar FROM samira.carritos_abandonados
                                                      where estado = 0 and vendedora = "PAGINA";');
        $carritosAgandonadosPendientes = DB::select('SELECT count(*) as Pendientes FROM samira.carritos_abandonados
                                                      where estado = 0 and vendedora <> "PAGINA";');
        $carritosAbandonadosSinNotas = DB::select('select id_carritos_abandonados as id_carritos, (select count(*) from samira.notas_carritos_abandonados
                                                    where id_carritos_abandonados = id_carritos) as cant_notas, count(*) as SinNotas
                                                    from samira.carritos_abandonados as carritos
                                                    where estado = 0 and vendedora <> "PAGINA"
                                                    group by cant_notas
                                                    having cant_notas = 0');

        $carritosAbandonados['carritosAgandonadosSinAsignar'] = $carritosAgandonadosSinAsignar[0]->SinAsignar;
        $carritosAbandonados['carritosAgandonadosPendientes'] = $carritosAgandonadosPendientes[0]->Pendientes;
        if (!empty($carritosAbandonadosSinNotas[0]->SinNotas)) {
            $carritosAbandonados['carritosAbandonadosSinNotas'] = $carritosAbandonadosSinNotas[0]->SinNotas;
        } else $carritosAbandonados['carritosAbandonadosSinNotas'] = 0;
        return Response::json($carritosAbandonados);
    }

    public function relojesOperativos()
    {
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', date("Y-m-d"))->toDateString();
        $fecha_fin = Carbon::createFromFormat('Y-m-d', date("Y-m-d"))->toDateString();
        $horaDesde = ' 00:00:00';
        $horaHasta = ' 23:59:59';
        $ultActualización = Carbon::createFromFormat('Y-m-d', date("Y-m-d"))->toDateString();
        $ultActuDesde = $ultActualización . $horaDesde;
        $ultActuHasta = $ultActualización . $horaHasta;
        $cantidadVentasSalon = DB::select('select count(*) as cantidad from samira.facturah as Facth
                                    left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and (Control.nrofactura is null
                                    or Control.ordenWeb is null
                                    or Control.ordenWeb = 0);');
        $cantidadPedidosFacturados = DB::select('select count(*) as cantidad from samira.facturah as Facth
                                    left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                    where Facth.Fecha >= "'. $fecha_inicio .'" and Facth.Fecha <= "'. $fecha_fin .'"
                                    and Control.ordenWeb is Not null
                                    and Control.ordenWeb <> 0;');
        $cantPedidosPasados = DB::select('select count(*) as cantPedidos
                                    FROM samira.controlpedidos
                                    where ultactualizacion > "' . $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                                    and total > 1
                                    and estado <> 2
                                    and ordenWeb > 0');
        $consulta['cantidadVentasSalon'] = $cantidadVentasSalon;
        $consulta['cantidadPedidosFacturados'] = $cantidadPedidosFacturados;
        $consulta['cantPedidosPasados'] = $cantPedidosPasados;
        return Response::json($consulta);
    }

    public function tablaPedidos()
    {
        $fecha_actual = date("Y-m-d");
        $fecha_limite = (date("Y-m-d",strtotime($fecha_actual."- 3 days")));
        $consulta = DB::select('SELECT ctrl.vendedora as vendedoraConsulta,
                                SUM(CASE WHEN ctrl.total < 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "EnProceso",
                                SUM(CASE WHEN ctrl.total > 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "ParaFacturar",
                                (SELECT
                                if ("'.$fecha_limite.'" <= ctrl.fecha, 1, 0) as suma
                                FROM samira.controlpedidos as ctrl
                                inner join samira.vendedores as vendedores ON vendedores.nombre = ctrl.vendedora
                                where ctrl.fecha > "2020-05-01" and
                                ctrl.vendedora not in ("Veronica"," ")
                                and vendedores.tipo <> 0
                                and ctrl.total < 1 and ctrl.estado = 1
                                and vendedora = vendedoraConsulta
                                group by suma
                                having suma = 0) as VencidosEnPreceso,
                                (SELECT
                                if ("'.$fecha_limite.'" <= ctrl.fecha, 1, 0) as suma
                                FROM samira.controlpedidos as ctrl
                                inner join samira.vendedores as vendedores ON vendedores.nombre = ctrl.vendedora
                                where ctrl.fecha > "2020-05-01" and
                                ctrl.vendedora not in ("Veronica"," ")
                                and vendedores.tipo <> 0
                                and ctrl.total > 1 and ctrl.estado = 1
                                and vendedora = vendedoraConsulta
                                group by suma
                                having suma = 0) as VencidosParaFacturar
                                FROM samira.controlpedidos as ctrl
                                inner join samira.vendedores as vendedores ON vendedores.nombre = ctrl.vendedora
                                where ctrl.fecha > "2020-05-01" and
                                ctrl.vendedora not in ("Veronica"," ")
                                and vendedores.tipo <> 0
                                group by vendedora;');
        return Response::json($consulta);
    }
}
