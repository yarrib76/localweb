<?php

namespace Donatella\Http\Controllers\Pedido;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function panel()
    {
        $facturados = DB::select('SELECT count(*) as count from samira.controlPedidos where estado = 0 and (empaquetado = 0 or empaquetado = 2)');
        $procesos = DB::select('SELECT count(*) as count from samira.controlPedidos where estado = 1');
        $empaquetados = DB::select('SELECT count(*) as count from samira.controlPedidos where estado = 0 and empaquetado = 1');
        $cancelados = DB::select('SELECT count(*) as count from samira.controlPedidos where estado = 2');
        $todos = DB::select('SELECT count(*) as count from samira.controlPedidos');
        return view('pedidos.panel_v2',compact('facturados','procesos','empaquetados','cancelados','todos'));
    }

    public function facturados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb, pedidos.instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 0 and (pedidos.empaquetado = 0 or pedidos.empaquetado = 2)
                    group by nropedido');

        $estado = 'Facturados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function procesados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb, pedidos.instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function empaquetados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb, pedidos.instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 0 and pedidos.empaquetado = 1
                    group by nropedido');

        $estado = 'Empaquetados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function cancelados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb, pedidos.instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 2
                    group by nropedido');

        $estado = 'Cancelados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function todos()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    group by nropedido');

        $estado = 'Todos';
        return view('pedidos.reporte', compact('pedidos','user_id','estado'));
    }
}
