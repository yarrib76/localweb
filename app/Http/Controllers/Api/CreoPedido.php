<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Models\ControlPedidos;
use Donatella\Models\PedidosTemp;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CreoPedido extends Controller
{
    public function inPedido()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $fechaHora = date("Y-m-d H:i:s");
        $datos =  Input::all();
        $validarPedido = PedidosTemp::where('nroPedido', $datos[0]['nroPedido'])->get();
        //Verifico si hay un pedido con el mismo numero. Si count es = 0 no hay pedidos y lo creo
        if (count($validarPedido) != 0) {
            DB::select('DELETE from samira.pedidotemp WHERE NroPedido = "'.$datos[0]['nroPedido'].'"');
            $estado = "ok";
        }else $estado = "ok";
            foreach ($datos as $dato){
                PedidosTemp::create([
                    'nroPedido' => $dato['nroPedido'],
                    'Articulo' => $dato['Articulo'],
                    'Detalle' => $dato['Detalle'],
                    'Cantidad' => $dato['Cantidad'],
                    'PrecioArgen' => $dato['PrecioArgen'],
                    'PrecioUnitario' => $dato['PrecioUnitario'],
                    'PrecioVenta' => $dato['PrecioVenta'],
                    'Ganancia' => $dato ['Ganancia'],
                    'Cajera' => 'None',
                    'Vendedora' => $dato['Vendedora'],
                    'Fecha' => $fecha,
                    'Estado' => '0'
                ]);
                $vendedora = $dato['Vendedora'];
            }
            $this->crearControlPedido($datos[0]['nroPedido'],$vendedora,$fecha ,$datos[0]['Total'],$datos[0]['OrdenWeb'],$fechaHora);
            return $estado;
    }

    public function crearControlPedido($nroPedido,$vendedora,$fecha,$total,$ordenWeb,$fechaHora)
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
                'ultactualizacion' => $fechaHora
            ]);
        }else{
            DB::select('UPDATE samira.controlpedidos SET total = "'. $total.'", ordenWeb = "'.$ordenWeb.'", vendedora = "'.$vendedora.'", ultactualizacion = "'.$fechaHora.'"
                        WHERE nroPedido = "'.$nroPedido.'";');
        }
        return;
    }
}
