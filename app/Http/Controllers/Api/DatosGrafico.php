<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class DatosGrafico extends Controller
{
    public function obtengoArticulo()
    {
        $nroArticulo= Input::get('nroarticulo');
        $anio = Input::get('anio');
        if (empty($anio)){
            $anio = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }

        $articulo = DB::table('Factura')
            ->select(
                DB::raw("Articulo as articulo"),
                DB::raw("sum(Cantidad) as cantidad"),
                DB::raw("DATE_FORMAT(fecha, '%m') as mes"))
            ->where('fecha', '>=' , $anio . '/01/01')
            ->where('fecha', '<=', $anio . '/12/31')
            ->where('Articulo', $nroArticulo)
            ->orderBy("Mes")
            ->groupBy(DB::raw("Mes"))
            ->get();

        $result[] = ['Mes','Cantidad'];
        $result = $this->llenoArray($result);
        foreach ($articulo as $key => $value) {
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
        $articulo = json_encode($result);

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

    public function obtengoArticuloVendedora()
    {
        $nroArticulo= Input::get('nroarticulo');
        $anio = Input::get('anio');
        if (empty($anio)){
            $anio = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }
        $articuloVendedora = DB::table('Factura')
            ->select(
                DB::raw("Vendedora as vendedora"),
                DB::raw("sum(Cantidad) as cantidad"))
            ->where('fecha', '>=' , $anio . '/01/01')
            ->where('fecha', '<=', $anio . '/12/31')
            ->where('Articulo', $nroArticulo)
            ->groupBy(DB::raw("Vendedora"))
            ->get();
        $result[] = ['Vendedora','Cantidad'];
        foreach ($articuloVendedora as $key => $value) {
            $result[++$key] = [$value->vendedora, (int)$value->cantidad];
        }
        return json_encode($result);
    }
}
