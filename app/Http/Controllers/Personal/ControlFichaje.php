<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ControlFichaje extends Controller
{
    protected $anio = '2023';
    public function control()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $control = DB::select('SELECT left(upper(date_format(fecha_ingreso, "%M")),3) as mes,date_format(fecha_ingreso, "%m") as numMes,
                                SUM(CASE
                                    WHEN dayofweek(fecha_ingreso) = 7 AND TIMEDIFF(TIME(fecha_ingreso), "09:00:00") > "00:05:00" THEN 1
                                    WHEN dayofweek(fecha_ingreso) <> 7 AND TIMEDIFF(TIME(fecha_ingreso), hora_ingreso) > "00:05:00" THEN 1
                                    ELSE 0
                                END) as cantidad
                                FROM samira.fichaje
                                inner join samira.users on users.id = fichaje.id_user
                                where id_user = 19
                                and year(fecha_ingreso) = "'.$this->anio.'"
                                group by month(fecha_ingreso);');
        return Response::json($control);
    }

    public function listaMensual()
    {
        
    }
}
