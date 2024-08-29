<?php

namespace Donatella\Http\Controllers\Pedido;

use Donatella\Http\Controllers\Api\Notificaciones;
use Donatella\Models\ControlPedidos;
use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AsignacionGeneral extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function inbox()
    {
        return view('pedidos.asignaciongeneral', compact('vendedoras'));
    }
    Public function query()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, concat(clientes.nombre, ",",
                    clientes.apellido) as cliente, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total, pedidos.totalweb as totalweb,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.local as local
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1
                    group by nropedido');

        ob_start('ob_gzhandler');
        return Response::json($pedidos);
    }

    public function vendedoras()
    {
        $arrVendedoras = [];
        $vendedoras = Vendedores::where('Tipo', '<>', 0)->get();
        for ($i = 0; $i < $vendedoras->count(); $i++ ){
            $arrVendedoras[$i] = [$vendedoras[$i]->Nombre => $vendedoras[$i]->Nombre ];
        }
        ob_start('ob_gzhandler');
        return Response::json($arrVendedoras);
    }

    public function update()
    {
        $datos = Input::all();
        $articulo = ControlPedidos::where('nroPedido', $datos['nropedido']);
        $articulo->update([
            'Vendedora' => $datos['vendedora']
        ]);
        $crearNotificacion = new Notificaciones();
        $crearNotificacion->crearNoti($datos['nropedido'],$datos['vendedora'],'Pedido');
        return;
    }
}
