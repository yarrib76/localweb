<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Estadisticas extends Controller
{
    public function index($id)
    {
        // $pathFoto = DB::select('select foto from samira.users where id= "'.$id.'"');
        return view('personal.estadistica', compact('id'));
    }

    public function pedidos()
    {
        $usuario_id = Input::get('usuario_id');
        $pedidos = $this->obtengoPedidos($usuario_id);
        return $pedidos;
    }
    public function obtengoFoto()
    {
        $usuario_id = Input::get('usuario_id');
        $fotoName=DB::select('select foto from samira.users where id="'.$usuario_id.'"');
        return $fotoName;
    }
    public function obtengoPedidos($usuario_id)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $anio = '2023';
        $pedidos = DB::select('SELECT upper(date_format(fecha, "%m")) as mes, count(*) as cantidad FROM samira.users
                                inner join samira.vendedores on vendedores.id = users.id_vendedoras
                                inner join samira.controlpedidos on controlpedidos.vendedora = vendedores.nombre
                                where id_vendedoras <> 31
                                and users.id = "'.$usuario_id.'"
                                and fecha >= "'.$anio.'""-01-01" and fecha <= "'.$anio.'""-12-31"
                                group by (month(fecha));');

        $result[] = ['Mes','Cantidad'];
        $result = $this->llenoArray($result);
        foreach ($pedidos as $key => $value) {
            switch((int)$value->mes) {
                Case 1:
                    $result[1] = ['Enero', (int)$value->cantidad];
                    break;
                Case 2:
                    $result[2] = ['Febrero', (int)$value->cantidad];
                    break;
                Case 3:
                    $result[3] = ['Marzo', (int)$value->cantidad];
                    break;
                Case 4:
                    $result[4] = ['Abril', (int)$value->cantidad];
                    break;
                Case 5:
                    $result[5] = ['Mayo', (int)$value->cantidad];
                    break;
                Case 6:
                    $result[6] = ['Junio',(int)$value->cantidad];
                    break;
                Case 7:
                    $result[7] = ['Julio', (int)$value->cantidad];
                    break;
                Case 8:
                    $result[8] = ['Agosto', (int)$value->cantidad];
                    break;
                Case 9:
                    $result[9] = ['Septiembre',(int)$value->cantidad];
                    break;
                Case 10:
                    $result[10] = ['Octubre',(int)$value->cantidad];
                    break;
                Case 11:
                    $result[11] = ['Noviembre',(int)$value->cantidad];
                    break;
                Case 12:
                    $result[12] = ['Diciembre',(int)$value->cantidad];
                    break;
            }
        }
        return json_encode($result);

        //   return view ('reporte.buscar', compact('articulo'));
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
    public function obtengoDatosPersonales(){
        $usuario_id = Input::get('usuario_id');
        $datos = DB::select('select nombre,apellido from samira.users
                            inner join samira.vendedores on vendedores.id = users.id_vendedoras
                            where users.id = "'.$usuario_id.'"');
        return Response::json($datos);
    }

    public function obtengoCantPedidos()
    {
        $anio = 2023;
        DB::statement("SET lc_time_names = 'es_ES'");
        $cantidadPedidos = DB::select('select left(upper(date_format(fecha, "%M")),3) mes, count(*) as cantidad from samira.controlpedidos
                                       where fecha >= "'.$anio.'""-01-01" and fecha <= "'.$anio.'""-12-31"
                                       group by (month(fecha));');
        return Response::json($cantidadPedidos);
    }

}
