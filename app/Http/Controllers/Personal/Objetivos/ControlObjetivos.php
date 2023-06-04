<?php

namespace Donatella\Http\Controllers\Personal\Objetivos;

use Donatella\Models\Objetivos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ControlObjetivos extends Controller
{
    public function listarObjetivos()
    {
        $usuario_id = Input::get('usuario_id');
        $datos = DB::select('select * from samira.objetivos
                             where id_users = "'.$usuario_id.'" ORDER BY FIELD(mes, "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");');
        return Response::json($datos);
    }

    public function update()
    {
        $objetivos = Input::get();
        $data = Objetivos::where('mes',$objetivos['mes'])
                            ->where('id_users',$objetivos['id_users']);
        $data->update([
            'fich_obj' => $objetivos['fich_obj'],
            'fich_alcance' => $objetivos['fich_alcance'],
            'ped_obj' => $objetivos['ped_obj'],
            'ped_alcance' => $objetivos['ped_alcance'],
            'v_salon_obj' => $objetivos['v_salon_obj'],
            'v_salon_alcance' => $objetivos['v_salon_alcance'],
            'cancel_obj' => $objetivos['cancel_obj'],
            'cancel_alcance' => $objetivos['cancel_alcance'],
            'no_encuesta_obj' => $objetivos['no_encuesta_obj'],
            'no_encuesta_alcance' => $objetivos['no_encuesta_alcance'],
            'fidel_obj' => $objetivos['fidel_obj'],
            'fidel_alcance' => $objetivos['fidel_alcance'],
            'id_users' => $objetivos['id_users']
        ]);
    }

    public function crearObjetivo()
    {
        $usuario_id = Input::get('usuario_id');
        $mes = Input::get('mes');
        $verificoExistenciaMes = DB::select('select * from samira.objetivos
                                            where mes = "'.$mes.'" and id_users = "'.$usuario_id.'"');
        if (!$verificoExistenciaMes){
            DB::select('INSERT INTO samira.objetivos (mes,id_users) VALUES("'.$mes.'","'.$usuario_id.'")');
            return Response::json('Se Creo Correctamente');
        } else return Response::json('El Mes Ya Existe');
    }

    public function resetObjetivos()
    {
        $usuario_id = Input::get('usuario_id');
        DB::select('delete from samira.objetivos where id_users = "'.$usuario_id.'" ');
        return Response::json("OK");
    }

    public function autoCargaObjetivos()
    {
        $mes = Input::get('mes');
        $usuario_id = Input::get('usuario_id');
        $porcentaje = Input::get('porcentaje');
        $tipo = Input::get('tipo');
        $data = Objetivos::whereRaw("LEFT(UPPER(mes), 3) = ". DB::raw("'$mes'"))
            ->where('id_users',$usuario_id);

        switch ($tipo){
            case "SinEncuesta":
                $data->update([
                    'no_encuesta_alcance' => $porcentaje
                ]);
                break;
            case "Pedidos":
                $data->update([
                    'ped_alcance' => $porcentaje
                ]);
                break;
            case "Salon":
                $data->update([
                    'v_salon_alcance' => $porcentaje
                ]);
                break;
            case "Cancelado":
                $data->update([
                    'cancel_alcance' => $porcentaje
            ]);
                break;
            case "Fichaje":
                $data->update([
                    'fich_alcance' => $porcentaje
                ]);
                break;
        }

    }
}
