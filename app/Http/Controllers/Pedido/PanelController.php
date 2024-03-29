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
        $pedidosPagados = DB::select('select count(*)as count from samira.controlpedidos where estado = 1 and pagado = 1');
        $todos = DB::select('SELECT count(*) as count from samira.controlPedidos');
        return view('pedidos.panel_v2',compact('facturados','procesos','empaquetados','cancelados','pedidosPagados','todos'));
    }

    public function facturados()
    {
        $user_id = Auth::user()->id;
        $estado = 'Facturados';
        return view('pedidos.facturados_reporte', compact('user_id','estado'));
    }
    public function procesados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta, pedidos.pagado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function pedidosPagos()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta, pedidos.pagado, fecha_pago
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and pedidos.pagado = 1
                    group by nropedido');

        $estado = 'Pagos';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function empaquetados()
    {
        $user_id = Auth::user()->id;
        DB::statement("SET lc_time_names = 'es_ES'");
        //Realizo esta operaci�n para saber si el pedido esta mas de x d�as facturado y en empaquetado
        $fecha_actual = date("Y-m-d");
        $fecha_limite = (date("Y-m-d",strtotime($fecha_actual."- 3 days")));
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden,
                    DATE_FORMAT(facturah.fecha, "%d de %M %Y") FechaFactura, facturah.fecha as fechaParaOrdenFact, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta,
                    CASE
                        WHEN "'.$fecha_limite.'" <= facturah.fecha then 1
                        ELSE 2
                    END as vencimiento
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    INNER JOIN samira.facturah as facturah ON facturah.NroFactura = pedidos.nrofactura
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
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta
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
        $estado = 'Todos';
        return view('pedidos.todos_reporte', compact('user_id',"estado"));
        // return view('pedidos.reporte', compact('pedidos','user_id','estado'));
    }
}
