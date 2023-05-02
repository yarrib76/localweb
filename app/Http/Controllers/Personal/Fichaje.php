<?php

namespace Donatella\Http\Controllers\Personal;

use Carbon\Carbon;
use Donatella\Models\FichajeDB;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Fichaje extends Controller
{
    public function index()
    {
        return view ('personal.fichaje_v2');
    }

    public function consultaEmpleado()
    {
        $codigo = Input::get('codigo');
        $empleado = DB::select('select name, foto from samira.users
                                where codigo = "'.$codigo.'"');
        return Response::json($empleado);
    }

    public function ingreso()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $codigo = Input::get('codigo');
        $user_id = DB::select('select id from samira.users
                               where codigo = "'.$codigo.'" ');
        $consulta = $this->validarIngreso($user_id);
        //Si la consulta esta vacia devuelve True entonces crea un fichaje
        if ($consulta['estado']){
            FichajeDB::create([
                'fecha_ingreso' => $fecha,
                'tipo' => 'I',
                'id_user' => $user_id[0]->id,
            ]);
            return "Que tenga un buen dia. Gracias!!!";
        } else return "Ya ficho en el dia de hoy";
    }

    public function egreso()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $codigo = Input::get('codigo');
        $user_id = DB::select('select id from samira.users
                               where codigo = "'.$codigo.'" ');
        $consulta = $this->validarIngreso($user_id);
        if (!$consulta['estado']){
            $fichaje = FichajeDB::where('id_fichaje',$consulta['id_fichaje']);
            $fichaje->update([
                'fecha_egreso' => $fecha,
                'id_user' => $user_id[0]->id,
            ]);
            return "Que tenga un buen dia. Gracias!!!";
        } else return "No ficho en el dia de hoy";
    }
    public function validarIngreso($user_id)
    {
        $fecha = Carbon::createFromFormat('Y-m-d', date("Y-m-d"))->format('Y-m-d');
        $consulta = DB::select('select * from samira.fichaje
                                where fecha_ingreso >= "'.$fecha.'" and fecha_ingreso <= "'.$fecha.'" "23:59:59"
                                and id_user = "'.$user_id[0]->id.'"');
        if (empty($consulta)){
            $consulta = ['estado' => true, 'id_fichaje' => ""];
            return $consulta;
        }else return $consulta = ['estado' => false, 'id_fichaje' => $consulta[0]->id_fichaje];;
    }
}
