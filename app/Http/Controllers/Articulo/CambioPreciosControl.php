<?php

namespace Donatella\Http\Controllers\Articulo;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CambioPreciosControl extends Controller
{
    public function index()
    {
        return view('articulos.cambioprecios');
    }


    public function proceso()
    {
        $proveedores = Input::get('proveedores');
        $calculo = Input::get('calculo');
        $tipo = Input::get('tipo');
        $proveedoresFormateados = $this->formateoProveedores($proveedores);
        if ($tipo == 'verificacion'){
            $resultado = $this->verificacion($proveedoresFormateados,$calculo);
            return $resultado;
        }
        if ($tipo == 'produccion') {
            $resultado = $this->produccion($proveedoresFormateados,$calculo);
            return $resultado;
        }
    }
    public function verificacion($proveedores,$calculo)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();

        // $articulos = DB::select('SELECT *
        //                        FROM samira.articulos
        //                        WHERE Proveedor IN ('. $string_coma .')');


        if ($calculo['tipo'] == 'porcentaje'){
            $resultadoPreview = DB::select("SELECT Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND(precioConvertido * 940, 2), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND(precioManual * 940, 2), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, PrecioOrigen as PrecioOrigenViejo, ROUND(precioOrigen * 940, 2) AS nuevoPrecioOrigen
                            FROM articulos
                            WHERE Proveedor IN ($proveedores)");

            return $resultadoPreview;
        }
        if ($calculo['tipo'] == 'dolar'){

        }
    }

    public function produccion($string_coma,$calculo)
    {
        if ($calculo['tipo'] == 'porcentaje' ){
            DB::transaction(function () use ($string_coma,$calculo){
                $valor = $calculo['valor'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND(precioConvertido * $valor,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($string_coma)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND(precioManual * $valor,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($string_coma)");

                // Actualiza precioOrigen x 1.5 independientemente de las condiciones anteriores y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioOrigen = ROUND(precioOrigen * $valor,2) WHERE Proveedor IN ($string_coma)");
            });
        }
        if ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] == ""){
            DB::transaction(function () use ($string_coma,$calculo){
                $valor = $calculo['valor'];
                // Actualiza precioConvertido cuancalculo['porcentajeDescuento']o precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND(precioOrigen * $valor,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($string_coma)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND(precioOrigen * $valor,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($string_coma)");
            });
        } elseif ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] != "") {
            DB::transaction(function () use ($string_coma,$calculo){
                $valor = $calculo['valor'];
                $porcentajeDescuento = $calculo['porcentajeDescuento'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND((precioOrigen * $valor)* $porcentajeDescuento,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($string_coma)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND((precioOrigen * $valor)* $porcentajeDescuento,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($string_coma)");
            });
        }

        if ($calculo['tipo'] == 'agregoQuito' ){
            DB::transaction(function () use ($string_coma,$calculo){
                $valorAgrego = $calculo['valorAgrego'];
                $valorQuito = $calculo['valorQuito'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND((precioConvertido / $valorQuito) * $valorAgrego,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($string_coma)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND((precioManual / $valorQuito) * $valorAgrego,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($string_coma)");

                // Actualiza precioOrigen x 1.5 independientemente de las condiciones anteriores y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioOrigen = ROUND((precioOrigen / $valorQuito) * $valorAgrego,2) WHERE Proveedor IN ($string_coma)");
            });
        }

        return "Terminado";
    }

    public function formateoProveedores($proveedores)
    {
        //Esta función se utiliza para poder hacer la consulta con la DB::
        function flatten_array($array) {
            $flatten = array();
            array_walk_recursive($array, function($a) use (&$flatten) { $flatten[] = $a; });
            return $flatten;
        }
        $flatten = flatten_array($proveedores);
        $quoted_elements = array_map(function($element){ return '"'.addslashes($element).'"'; }, $flatten);
        $string_coma = implode(",", $quoted_elements);

        return $string_coma;
    }
}
