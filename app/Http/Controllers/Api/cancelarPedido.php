<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Ayuda\GetPuntos;
use Donatella\Models\ControlPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class cancelarPedido extends Controller
{
    public function propuesta()
    {
        $datos = Input::all();
        $pedido = DB::select ('select nropedido, totalweb, id_cliente,id from samira.controlpedidos
                              where nropedido = "'.$datos['nroPedido'].'";');
        $puntos = new GetPuntos();
        $puntos = $puntos->calcularPuntos($pedido[0]->id_cliente,$pedido[0]->totalweb);
        $propuesta = DB::select ('SELECT descripcion, puntos, id
                                    FROM samira.parametros_de_negocio as nego
                                    inner join (SELECT max(puntos) as MaxPunto from samira.parametros_de_negocio
                                    where puntos <= "'.$puntos.'") as max ON max.MaxPunto = nego.Puntos');
        $cliente = DB::select('SELECT concat(nombre," ", apellido) as nombre FROM samira.clientes
                                where id_clientes = "'.$pedido[0]->id_cliente.'";');
        //Si tiene puntos crea propuesta
        if ($propuesta[0]->puntos > 0){
            $this->crearPropuesta($pedido,$puntos,$propuesta);
        }
        $propuestaCompleta[0] = ['descripcion' => $propuesta[0]->descripcion];
        $propuestaCompleta[1] = ['puntos' => $propuesta[0]->puntos];
        $propuestaCompleta[2] = ['nombreCliente' => $cliente[0]->nombre];
        return Response::json($propuestaCompleta);
    }

    public function cancelar()
    {
        $datos = Input::all();
        $pedido = ControlPedidos::where('nroPedido', $datos['nroPedido'])->get();
        //cambio el estado para que el pedido quede cancelado
        $pedido[0]->update([
            'Estado' => '2'
        ]);
        //Cambio el Tipo a 2 para indicar que no se acepto la propuesta y se cancelo el pedido
        DB::select('UPDATE `samira`.`control_pedidos_cancelados` SET `tipo` = "2"
                    WHERE (`id_control_pedidos` = "'.$pedido[0]->id.'");');
        $respuesta = '{"respuesta":["OK"]}';
        return Response::json($respuesta);
    }

    private function crearPropuesta($pedido,$puntos,$propuesta)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $ctrlPedidosCancelados = DB::select('select * from samira.control_pedidos_cancelados
                                              where id_control_pedidos = "'.$pedido[0]->id.'"');
        //Si el pedido no tiene propuesta creo una
        if (!$ctrlPedidosCancelados){
            DB::select('INSERT INTO `samira`.`control_pedidos_cancelados` (`id_control_pedidos`, `fecha`, `tipo`, `puntos`, `id_parametros_de_negocio`)
                        VALUES ("'.$pedido[0]->id.'", "'.$fecha.'", "1", "'.$puntos.'", "'.$propuesta[0]->id.'");');
        }
    }
}
