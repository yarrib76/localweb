<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ReportesDashboard extends Controller
{

    public function ventas()
    {
        $año = Input::get('anio');
        if (empty($año)){
            $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }
        DB::statement("SET lc_time_names = 'es_ES'");
        $query = DB::select('SELECT DATE_FORMAT(fecha, "%M") AS Mes, ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE total END),2) as total
                            from samira.facturah where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31" group by Mes');
        $result[] = ['Mes','Total'];
        foreach ($query as $key => $value) {
            $result[++$key] = [$value->Mes, (int)$value->total];
        }
        return $result;
    }

    public function vendedoras()
    {
        $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        $query = DB::select('SELECT Vendedora, sum(PrecioVenta) as Total FROM samira.factura where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31"
                            group by Vendedora order by Total DESC;');
        $result[] = ['Vendedora','Total'];
        foreach ($query as $key => $value) {
            $result[++$key] = [$value->Vendedora, (int)$value->Total];
        }
         return $result;
    }
}
