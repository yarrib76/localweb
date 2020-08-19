<?php

namespace Donatella\Http\Controllers\Contabilidad;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ReporteFinanciero extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function query()
    {
        $año = Input::get('anio');
        if (empty($año)){
            $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
            $datos = $this->getData($año);
            return view('contabilidad.reportefinanciero', compact('datos','año'));
        }
        $datos = $this->getData($año);
        return view('contabilidad.reportefinanciero', compact('datos','año'));
    }

    public function getData($año)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $query = DB::select('SELECT UPPER(DATE_FORMAT(fecha, "%M")) as Mes, ROUND(sum(Ganancia),2) As Ganancia, Fecha
                                FROM samira.facturah
                                WHERE fecha >=  "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31"
                                GROUP BY Mes
                                ORDER BY fecha asc;');
        return $query;
    }

    public function getDataGraficoGanancia()
    {
        $año = Input::get('anio');
        if (empty($año)) {
            $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }

        DB::statement("SET lc_time_names = 'es_ES'");
        $query = DB::select('SELECT UPPER(DATE_FORMAT(fecha, "%M")) as Mes, ROUND(sum(Ganancia),2) As Ganancia, Fecha
                                FROM samira.facturah
                                WHERE fecha >=  "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31"
                                GROUP BY Mes
                                ORDER BY fecha asc;');
        $result[] = ['Mes','Total'];
        foreach ($query as $key => $value) {
            $result[++$key] = [$value->Mes, (int)$value->Ganancia];
        }
        return $result;
    }

    public function getDataGraficoFacturacion()
    {
        $año = Input::get('anio');
        if (empty($año)){
            $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }
        DB::statement("SET lc_time_names = 'es_ES'");
        $query = DB::select('SELECT DATE_FORMAT(fecha, "%M") AS Mes, ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE total END),2) as total
                            from samira.facturah where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31"
                            group by Mes
                            ORDER BY fecha asc');
        $result[] = ['Mes','Total'];
        foreach ($query as $key => $value) {
            $result[++$key] = [$value->Mes, (int)$value->total];
        }
        return $result;
    }

    //Genero el reporte de facturaciòn mensual de cada vendedor segùn selecciòn del gràfico
    public function getDataFacturacionVendedores()
    {
        $año = Input::get('anio');
        $mes = Input::get('mes');
        $mes = $this->conviertoMesToNumero($mes);
       /** $query = DB::select('SELECT Vendedora,round(sum(PrecioVenta),2)as Total
                             FROM samira.factura
                             where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31" and month(fecha) = "'.$mes.'"
                             group by Vendedora
                             order by Total desc;'); */
        $query = DB::select('SELECT Vendedora,round(sum(PrecioVenta),2)as Total, round(sum(PrecioVenta * 100)/ (SELECT round(sum(PrecioVenta),2)
                             FROM samira.factura
                             where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31" and month(fecha) = "'.$mes.'"),2) as Porcentaje
                             FROM samira.factura
                             where fecha >= "' . $año .'/01/01" and Fecha <= "' . $año .'/12/31" and month(fecha) = "'.$mes.'"
                             group by Vendedora
                             order by Total desc;');
        return $query;
    }

    /**
     * @param $mes
     */
    public function conviertoMesToNumero($mes)
    {
        $result = 0;
        switch ($mes) {
            Case 'enero': $result = 1;
                break;
            Case 'febrero': $result = 2;
                break;
            Case 'marzo': $result = 3;
                break;
            Case 'abril': $result = 4;
                break;
            Case 'mayo': $result = 5;
                break;
            Case 'junio': $result = 6;
                break;
            Case 'julio': $result = 7;
                break;
            Case 'agosto': $result = 8;
                break;
            Case 'septiembre': $result = 9;
                break;
            Case 'octubre': $result = 10;
                break;
            Case 'noviembre': $result = 11;
                break;
            Case 'diciembre': $result = 12;
                break;
        }
        return $result;
    }
}
