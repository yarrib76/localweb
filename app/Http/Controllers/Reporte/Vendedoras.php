<?php

namespace Donatella\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Vendedoras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function pedidos()
    {
        $consultas = DB::select ('SELECT ctrl.vendedora,
                                SUM(CASE WHEN ctrl.estado = 1 THEN 1 ELSE 0 END) as "Asignados",
                                SUM(CASE WHEN ctrl.estado = 0 and  ctrl.empaquetado = 1 THEN 1 ELSE 0 END) as Empaquetado,
                                SUM(CASE WHEN ctrl.total < 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "EnProceso",
                                SUM(CASE WHEN ctrl.total > 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "ParaFacturar"
                                FROM samira.controlpedidos as ctrl
                                where ctrl.fecha > "2020-05-01" and
                                ctrl.vendedora not in ("Veronica"," ")
                                group by vendedora;');
        return view('reporte.reportevendedoras', compact('consultas'));
    }

    public function asignados()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function enProceso()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and pedidos.total < 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function paraFacturar()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and pedidos.total > 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function empaquetados()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 0 and pedidos.empaquetado = 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
}
