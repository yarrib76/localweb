<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ControlFichaje extends Controller
{
    protected $anio;
    public function __construct()
    {
        $this->anio = date('Y');
    }
    public function control()
    {
        $usuario_id = Input::get('usuario_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $control = DB::select('SELECT left(upper(date_format(fecha_ingreso, "%M")),3) as mes,date_format(fecha_ingreso, "%m") as numMes,
                                SUM(CASE
                                    WHEN dayofweek(fecha_ingreso) = 7 AND TIMEDIFF(TIME(fecha_ingreso), "09:00:00") > "00:05:00" THEN 1
                                    WHEN dayofweek(fecha_ingreso) <> 7 AND TIMEDIFF(TIME(fecha_ingreso), hora_ingreso) > "00:05:00" THEN 1
                                    ELSE 0
                                END) as cantidad
                                FROM samira.fichaje
                                inner join samira.users on users.id = fichaje.id_user
                                where id_user = "'.$usuario_id.'"
                                and year(fecha_ingreso) = "'.$this->anio.'"
                                group by month(fecha_ingreso);');
        return Response::json($control);
    }

    public function listaMensual()
    {
        $usuario_id = Input::get('usuario_id');
        $mes = Input::get('numMes');
        DB::statement("SET lc_time_names = 'es_ES'");
        $listaMensual = DB::select('SELECT upper(date_format(fecha_ingreso, "%W %e")) as mes, TIME(fecha_ingreso) horarioIngreso, TIME(fecha_egreso) horarioEgreso,
                                    CASE
                                        WHEN dayofweek(fecha_ingreso) = 7 AND TIMEDIFF(TIME(fecha_ingreso), "09:00:00") > "00:05:00" THEN 1
                                        WHEN dayofweek(fecha_ingreso) = 7 AND TIMEDIFF(TIME(fecha_ingreso), "09:00:00") > "00:00:01" AND TIMEDIFF(TIME(fecha_ingreso), "09:00:00") < "00:05:00" THEN 2
                                        WHEN dayofweek(fecha_ingreso) <> 7 AND TIMEDIFF(TIME(fecha_ingreso), hora_ingreso) > "00:05:00" THEN 1
                                        WHEN dayofweek(fecha_ingreso) <> 7 AND TIMEDIFF(TIME(fecha_ingreso), hora_ingreso) > "00:00:01" AND TIMEDIFF(TIME(fecha_ingreso), hora_ingreso) < "00:05:00" THEN 2
                                        ELSE 0
                                    END as fichaje
                                    FROM samira.fichaje
                                    inner join samira.users on users.id = fichaje.id_user
                                    where id_user = "'.$usuario_id.'"
                                    and year(fecha_ingreso) = "'.$this->anio.'"
                                    and month(fecha_ingreso) = "'.$mes.'"
                                    order by fecha_ingreso ASC');

      return Response::json($listaMensual);
    }

    public function cantDiasAusentes()
    {
        $usuario_id = Input::get('usuario_id');
        $consulta = DB::select('SELECT
                                        COUNT(*) AS dias_trabajados,
                                            left(upper(date_format(fecha_ingreso, "%M")),3) AS mesName,
                                            date_format(fecha_ingreso, "%m") AS mes
                                            FROM fichaje f
                                        WHERE DAYOFWEEK(fecha_ingreso) BETWEEN 2 AND 7
                                        AND YEAR(fecha_ingreso) = "'.$this->anio.'"
                                        and id_user	= "'.$usuario_id.'"
                                        GROUP BY mes');
// Procesar el resultado de la consulta
        foreach ($consulta as $fila) {
            // Llamar a la funci�n prueba() con el valor de $mes
            $diferencia = $this->procesoFecha($usuario_id,$fila->mes);
            // Agregar los resultados al arreglo
            $resultados[] = array(
                'dias_trabajados' => $fila->dias_trabajados,
                'mesName' => $fila->mesName,
                'mes' => $fila->mes,
                'diferencia' => $diferencia
            );
        }
        return Response::json($resultados);
    }

    public function listaDiasAusentes(){
        $usuario_id = Input::get('usuario_id');
        $mes = Input::get('numMes');
        $consulta = DB::select('SELECT fecha as mes
                                    FROM (
                                        SELECT DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY) AS fecha, n.n
                                        FROM (
                                            SELECT a.N + b.N * 10 n
                                            FROM (
                                                SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                            ) a
                                            CROSS JOIN (
                                                SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                            ) b
                                        ) n
                                        WHERE MONTH(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) = "'.$mes.'"
                                            AND DAY(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) >= 1
                                            AND DAYOFWEEK(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) != 1
                                    ) subquery
                                    WHERE fecha NOT IN (
                                        SELECT DATE_FORMAT(fecha_ingreso, "%Y-%m-%d")
                                        FROM fichaje
                                        WHERE YEAR(fecha_ingreso) = "'.$this->anio.'"
                                            AND MONTH(fecha_ingreso) = "'.$mes.'"
                                            AND id_user = "'.$usuario_id.'"
                                    )
                                    ORDER BY n;
                                    ');
        return Response::json($consulta);
    }

    //La funci�n devuelve la cantidad de d�as que no se ficho descartando los domingos. Ser�an los d�as que faltaron
    public function procesoFecha($usuario_id,$mes)
    {
        $consulta = DB::select('SELECT COUNT(*) AS cantidad_resultados
                FROM (SELECT fecha as mes
                                    FROM (
                                        SELECT DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY) AS fecha, n.n
                                        FROM (
                                            SELECT a.N + b.N * 10 n
                                            FROM (
                                                SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                            ) a
                                            CROSS JOIN (
                                                SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                            ) b
                                        ) n
                                        WHERE MONTH(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) = "'.$mes.'"
                                            AND DAY(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) >= 1
                                            AND DAYOFWEEK(DATE_ADD(CONCAT("'.$this->anio.'", "-", "'.$mes.'", "-01"), INTERVAL n.n DAY)) != 1
                                    ) subquery
                                    WHERE fecha NOT IN (
                                        SELECT DATE_FORMAT(fecha_ingreso, "%Y-%m-%d")
                                        FROM fichaje
                                        WHERE YEAR(fecha_ingreso) = "'.$this->anio.'"
                                            AND MONTH(fecha_ingreso) = "'.$mes.'"
                                            AND id_user = "'.$usuario_id.'"
                                    )
                                    ORDER BY n
                                    ) result_count;
                ');

        return $consulta[0]->cantidad_resultados;
    }
}
