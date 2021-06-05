<?php
/**
 * Created by PhpStorm.
 * User: viamore
 * Date: 05/29/2021
 * Time: 07:34 PM
 */

namespace Donatella\Ayuda;


use Illuminate\Support\Facades\DB;

class GetPuntos
{
    public function calcularPuntos($id_cliente,$totalPedido){
        $historial[] = "";
        $historial[0] = ['TotalPedido' => $totalPedido];
        $historial = $this->historialCompras($id_cliente,$historial);
        $historial = $this->historialPromo($id_cliente,$historial);
        $puntosObtenidos = $this->analisisPuntos($historial);
        return $puntosObtenidos;
    }

    private function historialCompras($id_cliente,$historial)
    {
        $cantCompras = DB::select(' SELECT count(*) as Compras FROM samira.controlpedidos
                                    where id_cliente = "'. $id_cliente .'";');
        $monto =  DB::select(' SELECT sum(Total) as Total FROM samira.controlpedidos
                               where id_cliente = "'. $id_cliente .'";');
        $cantOfertasDisponibles = DB::select('select cant_ofertas from samira.clientes
                                    where id_clientes = "'. $id_cliente .'"');
        $historial[1] = ['cantCompasHistorico' => $cantCompras[0]->Compras];
        $historial[2] = ['montoTotalHistorico' => $monto[0]->Total];
        $historial[3] = ['cant_ofertasDisponibles' => $cantOfertasDisponibles[0]->cant_ofertas];
        return $historial;
    }

    private function historialPromo($id_cliente, $historial)
    {
        $cantOfertasAceptadas = DB::select('SELECT count(*) as CantidadOfertas
                                                FROM samira.control_pedidos_cancelados as CtrolPedidosCancelados
                                                inner join samira.controlpedidos as CtrolPedidos ON CtrolPedidos.id = CtrolPedidosCancelados.id_control_pedidos
                                                where CtrolPedidos.id_cliente = "'.$id_cliente.'"
                                                and CtrolPedidosCancelados.tipo = 1 ;');
        $historial[4] = ['cantOfertasAceptadas' =>$cantOfertasAceptadas[0]->CantidadOfertas];
        return $historial;
    }

    private function analisisPuntos($historial)
    {
        $puntos = 0;
        if ($historial[0]['TotalPedido'] >= 3000 and $historial[4]['cantOfertasAceptadas'] <= $historial[3]['cant_ofertasDisponibles']){
            $puntos = 50;
                if ($historial[1]['cantCompasHistorico'] >= 3) {
                    $puntos = $puntos + 50;
                }
                if ($historial[2]['montoTotalHistorico'] >= 20000) {
                    $puntos = $puntos + 350;
                }
                if ($historial[0]['TotalPedido'] >= 5000 and $historial[0]['TotalPedido'] <= 15000){
                    $puntos = $puntos + 1000;
                }
                if ($historial[0]['TotalPedido'] > 15000 and $historial[0]['TotalPedido'] <= 20000) {
                    $puntos = $puntos + 2000;
                }
                if ($historial[0]['TotalPedido'] > 20000) {
                    $puntos = $puntos + 5000;
                }
        }elseif ($historial[4]['cantOfertasAceptadas'] >= $historial[3]['cant_ofertasDisponibles']){
            $puntos = -1;
        }
        return $puntos;
    }
}