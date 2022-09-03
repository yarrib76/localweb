<?php

namespace Donatella\Http\Controllers\Api\Pedidos;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class GetPedidos extends Controller
{
    public function todos()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS Fecha, pedidos.fecha as FechaParaOrden, nroPedido as NroPedido,
                    concat(clientes.nombre,",",clientes.apellido) as Cliente, pedidos.nrofactura as Factura, pedidos.vendedora as Vendedora,
                    pedidos.id as id, pedidos.total as Total, pedidos.ordenweb as OrdenWeb, comentarios.comentario as Comentarios,
                    pedidos.empaquetado as Empaquetado, pedidos.transporte as Transporte, pedidos.instancia,clientes.id_clientes,
                    CASE
                        WHEN pedidos.estado = 0 and pedidos.empaquetado = 1 THEN "Empaquetado"
                        WHEN pedidos.estado = 0 THEN "Facturado"
                        WHEN pedidos.estado = 1 THEN "Procesando"
                        ELSE "Cancelado"
                    END as Estado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    group by nropedido');

        ob_start('ob_gzhandler');
        return Response::json($pedidos);
    }

    public function facturados()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS Fecha, pedidos.fecha as FechaParaOrden, nroPedido as NroPedido,
                    concat(clientes.nombre,",",clientes.apellido) as Cliente, pedidos.nrofactura as Factura, pedidos.vendedora as Vendedora,
                    pedidos.id as id, pedidos.total as Total, pedidos.ordenweb as OrdenWeb, comentarios.comentario as Comentarios,
                    pedidos.empaquetado as Empaquetado, pedidos.transporte as Transporte, pedidos.totalweb as TotalWeb,
                    clientes.id_clientes, clientes.encuesta,
                    CASE
                        WHEN pedidos.estado = 0 and pedidos.empaquetado = 1 THEN "Empaquetado"
                        WHEN pedidos.estado = 0 THEN "Facturado"
                        WHEN pedidos.estado = 1 THEN "Procesando"
                        ELSE "Cancelado"
                    END as Estado,
                    CASE
                      WHEN pedidos.instancia = 0 THEN "Pendiente"
                      WHEN pedidos.instancia = 1 THEN "Iniciado"
                      WHEN pedidos.instancia = 2 THEN "Finalizado"
                    END as Instancia
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 0 and (pedidos.empaquetado = 0 or pedidos.empaquetado = 2)
                    group by nropedido');

        ob_start('ob_gzhandler');
        return Response::json($pedidos);
    }
}
