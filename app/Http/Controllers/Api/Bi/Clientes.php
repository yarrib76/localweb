<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Clientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function query()
    {
        $año = Input::get('anio');
        $id_cliente = Input::get('id_cliente');
        if (empty($id_cliente)){
            if (empty($año)){
                $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
            }
            DB::statement("SET lc_time_names = 'es_ES'");
            $clientes = DB::select('select CONCAT (cli.nombre, "," , cli.apellido) as Cliente, sum(facth.Total) as Total, cli.id_clientes as Id,
                                count(distinct month(facth.Fecha)) as Meses, regllamadas.comentarios
                                from samira.facturah as facth
                                inner join samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                                left join samira.registrollamadas as regllamadas ON regllamadas.clientes_id = cli.id_clientes
                                where cli.nombre <> "Ninguno" and facth.Estado <> 2 and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31"
                                GROUP BY facth.id_clientes ORDER BY Total DESC ;');
            $user_id = Auth::user()->id;
            return view('bi.clientes', compact('clientes', 'año','user_id'));
        }
        $cliente = $this->queryGradico($id_cliente,$año);
        return Response::json($cliente);
    }

    public function queryGradico($id_cliente,$año)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $cliente = DB::select('select CONCAT (cli.nombre, "," , cli.apellido) as Cliente, sum(facth.Total) as Total,
                                cli.id_clientes as Id, DATE_FORMAT(facth.Fecha, "%m") as mes
                                from samira.facturah as facth
                                inner join samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                                where facth.id_clientes =  "'. $id_cliente .'" and facth.Estado <> 2 and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31"
                                GROUP BY Mes ORDER BY Total DESC ;');
        $result[] = ['Mes','Cantidad'];
        $result = $this->llenoArray($result);
        foreach ($cliente as $key => $value) {
            switch((int)$value->mes) {
                Case 1:
                    $result[1] = ['Enero', (int)$value->Total];
                    break;
                Case 2:
                    $result[2] = ['Febrero', (int)$value->Total];
                    break;
                Case 3:
                    $result[3] = ['Marzo', (int)$value->Total];
                    break;
                Case 4:
                    $result[4] = ['Abril', (int)$value->Total];
                    break;
                Case 5:
                    $result[5] = ['Mayo', (int)$value->Total];
                    break;
                Case 6:
                    $result[6] = ['Junio',(int)$value->Total];
                    break;
                Case 7:
                    $result[7] = ['Julio', (int)$value->Total];
                    break;
                Case 8:
                    $result[8] = ['Agosto', (int)$value->Total];
                    break;
                Case 9:
                    $result[9] = ['Septiembre',(int)$value->Total];
                    break;
                Case 10:
                    $result[10] = ['Octubre',(int)$value->Total];
                    break;
                Case 11:
                    $result[11] = ['Noviembre',(int)$value->Total];
                    break;
                Case 12:
                    $result[12] = ['Diciembre',(int)$value->Total];
                    break;
            }
        }
        $articulo = json_encode($result);

        return ($result);
        return json_encode($result);
    }

    public function llenoArray($result)
    {
        $result[1] = ['Enero',0];
        $result[2] = ['Febrero',0];
        $result[3] = ['Marzo',0];
        $result[4] = ['Abril',0];
        $result[5] = ['Mayo',0];
        $result[6] = ['Junio',0];
        $result[7] = ['Julio',0];
        $result[8] = ['Agosto',0];
        $result[9] = ['Septiembre',0];
        $result[10] = ['Octubre',0];
        $result[11] = ['Noviembre',0];
        $result[12] = ['Diciembre',0];
        return $result;
    }
}
