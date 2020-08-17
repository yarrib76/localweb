<?php

namespace Donatella\Http\Controllers\Pedido;

use Donatella\Models\ControlPedidos;
use Donatella\Models\PedidosTemp;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $role = Auth::user()->id_roles;
        DB::statement("SET lc_time_names = 'es_ES'");
        /* Si el rol es de vendedora solo miestro los pedidos en proceso, es para agilizar la vista en la tablet*/
        if ($role == 3){
            $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 or pedidos.estado = 2
                    group by nropedido');
        } else {
            $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    group by nropedido');
            // dd($pedidos[0]->nroPedido);
        }

        $estado = 'Todo';
        return view('pedidos.reporte', compact('pedidos','user_id','estado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pedido = PedidosTemp::where('NroPedido', '=', $id);
        $pedido->delete();
        return redirect()->route('pedidos.index');
    }
}
