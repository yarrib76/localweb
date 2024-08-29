<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Notificaciones extends Controller
{
    public function getData()
    {
        $id_users = Input::get('id_users');
        $data = DB::select('select * from samira.notificaciones
                              WHERE id_users = "'.$id_users.'" and lectura = 0;');
        return Response::json($data);
    }

    public function getCantNoti()
    {
        $id_users = Input::get('id_users');
        $data = DB::select('select count(*) as cantidad from samira.notificaciones
                            where id_users = "'.$id_users.'" and lectura = 0;');

        return $data[0]->cantidad;
    }

    public function marcarComoLeido()
    {
        $id_noti = Input::get('id_noti');
        DB::select('update samira.notificaciones set lectura = 1
                    where id_notificaciones = "'.$id_noti.'";');
        return Response::json("OK");
    }

    public function crearNoti($nroPedido_carrito, $vendedora, $tipo)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $id_users = DB::select('select U.id from samira.users as U
                                    inner join samira.vendedores as V on V.id = U.id_vendedoras
                                    where V.nombre = "'. $vendedora .'"');
        switch ($tipo){
            case "Pedido":DB::select('INSERT INTO samira.notificaciones (fecha,tipo,id_users)
                                      values ("' .$fecha . '", CONCAT("Se le asigno el " , "'. $tipo .'" , " Nro: " , "'. $nroPedido_carrito .'") , "'. $id_users[0]->id .'")');
                break;
            case "Carrito": $nombre_Contacto = DB::select('select nombre_contacto from samira.carritos_abandonados
                                                            where id_carritos_abandonados = "'. $nroPedido_carrito . '"');
                             DB::select('INSERT INTO samira.notificaciones (fecha,tipo,id_users)
                                      values ("' .$fecha . '", CONCAT("Se le asigno el " , "'. $tipo .'" , " de " , "'. $nombre_Contacto[0]->nombre_contacto .'") , "'. $id_users[0]->id .'")');
                break;
        }
    }
}
