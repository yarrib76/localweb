<?php

namespace Donatella\Http\Controllers\PedidosWeb;

use Carbon\Carbon;
use Donatella\Models\ControlPedidos;
use Donatella\Models\PedidosTemp;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ControllerPedidoWeb extends Controller
{
    public function view(Request $request)
    {
        $nameCajera = Auth::user()->name;
        return view('pedidoweb.pedidoview', compact('nameCajera'));
    }

    public function getNroPedido(){
        $nroFactura = DB::select('select * from samira.nrofactura');
        return $nroFactura;
    }

    public function inPedido()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $fechaHora = date("Y-m-d H:i:s");
        $datos = json_decode((Input::get('articulos')));
        $total = Input::get('total');
        $cliente_id = Input::get('cliente_id');
        $ordenWeb = Input::get('ordeWeb');
        $nroPedido = Input::get('nroPedido');
        $vendedora = Input::get('vendedora');
        $validarPedido = PedidosTemp::where('nroPedido', $nroPedido)->get();
        //Verifico si hay un pedido con el mismo numero. Si count es = 0 no hay pedidos y lo creo
        if (count($validarPedido) != 0) {
            DB::select('DELETE from samira.pedidotemp WHERE NroPedido = "'.$nroPedido.'"');
            $estado = "ok";
        }else $estado = "ok";
        foreach ($datos as $dato){
            PedidosTemp::create([
                'nroPedido' => $nroPedido,
                'Articulo' => $dato->Articulo,
                'Detalle' => $dato->Detalle,
                'Cantidad' => $dato->Cantidad,
                'PrecioArgen' => $dato->PrecioArgen,
                'PrecioUnitario' => $dato->PrecioUnitario,
                'PrecioVenta' => $dato->PrecioVenta,
                'Ganancia' => $dato->Ganancia,
                'Cajera' => $dato->Cajera,
                'Vendedora' => $dato->Vendedora,
                'Fecha' => $fecha,
                'Estado' => '0'
            ]);
            // $vendedora = $dato->Vendedora;
        }
        $this->crearControlPedido($nroPedido,$vendedora,$fecha ,$total,$ordenWeb,$fechaHora, $cliente_id);
        return $estado;
    }

    public function crearControlPedido($nroPedido,$vendedora,$fecha,$total,$ordenWeb,$fechaHora, $cliente_id)
    {
        $validarPedido = ControlPedidos::where('nroPedido', $nroPedido)->get();
        //Verifico si hay un pedido con el mismo numero. Si count es = 0 no hay pedidos y lo creo
        if (count($validarPedido) == 0) {
            ControlPedidos::create([
                'nroPedido' => $nroPedido,
                'Vendedora' => $vendedora,
                'Fecha' => $fecha,
                'Total' => $total,
                'OrdenWeb' => $ordenWeb,
                'ultactualizacion' => $fechaHora,
                'id_cliente' => $cliente_id,
            ]);
        }else{
            DB::select('UPDATE samira.controlpedidos SET total = "'. $total.'", ordenWeb = "'.$ordenWeb.'", vendedora = "'.$vendedora.'", ultactualizacion = "'.$fechaHora.'",
                        id_cliente = "'.$cliente_id.'"
                        WHERE nroPedido = "'.$nroPedido.'";');
        }
        return;
    }
}
