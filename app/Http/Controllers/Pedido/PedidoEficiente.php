<?php

namespace Donatella\Http\Controllers\Pedido;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class PedidoEficiente extends Controller
{
    public function index()
    {
        $vendedora = Input::get('vendedora');
        $nroPedido = Input::get('nroPedido');
        $articulosEnPedidos = DB::SELECT('SELECT ordenArti.articulo as NroArticulo, ordenArti.detalle as Detalle,
                                            (SELECT count(ordenArti.articulo) as EnPedidos FROM samira.controlpedidos as ctrlPedido
                                            inner join samira.ordenesarticulos as ordenArti ON ordenArti.id_controlPedidos = ctrlPedido.id
                                            where ctrlPedido.estado = 1 and ctrlPedido.total < 1 and vendedora = "'. $vendedora .'" and ctrlPedido.instancia = 1
                                            and ordenArti.articulo = NroArticulo
                                            and ordenArti.estado_Arti_Pedido = 0
                                            group by (ordenArti.articulo)
                                            having EnPedidos > 1) as EnPedidos
                                            FROM samira.controlpedidos as ctrlPedido
                                            inner join samira.ordenesarticulos as ordenArti ON ordenArti.id_controlPedidos = ctrlPedido.id
                                            where ctrlPedido.estado = 1 and ctrlPedido.total < 1 and vendedora = "'. $vendedora .'" and ctrlPedido.instancia = 1
                                            and nropedido = "'. $nroPedido . '"
                                            having EnPedidos > 1');
        return view('pedidos.eficiente.reporte', compact('articulosEnPedidos','vendedora','nroPedido'));
    }

    public function articuloPedidos()
    {

        $vendedora = Input::get('vendedora');
        $articulo = Input::get('nroArticulo');
        $query = DB::SELECT('SELECT ctrlPedido.NroPedido, ctrlPedido.OrdenWeb, ordenArti.articulo as Articulo,
                                ordenArti.detalle as Detalle, ordenArti.cantidad as Cantidad, Arti.Cantidad as Stock
                                FROM samira.controlpedidos as ctrlPedido
                                inner join samira.ordenesarticulos as ordenArti ON ordenArti.id_controlPedidos = ctrlPedido.id
                                inner join samira.articulos as Arti on Arti.Articulo = ordenArti.Articulo
                                where ctrlPedido.estado = 1 and ctrlPedido.total < 1 and vendedora = "'. $vendedora .'" and instancia = 1
                                and ordenArti.estado_Arti_Pedido = 0
                                and ordenArti.articulo = "'.$articulo.'";');
        return Response::json($query);
    }

    //La funcion cambia el estado del campo estado_Arti_Pedido a 1 indicando que ya se agrego ese articulo al pedido.
    public function agregar()
    {
        DB::SELECT('UPDATE samira.controlpedidos as ctrlPedidos
                    inner join samira.ordenesarticulos as ordenArti ON ordenArti.id_controlPedidos = ctrlPedidos.id
                    SET estado_Arti_Pedido = 1
                    where ctrlPedidos.nropedido = "'. Input::get('NroPedido') .'"
                    and ordenArti.articulo = "'. Input::get('Articulo') .'"');
    }
}