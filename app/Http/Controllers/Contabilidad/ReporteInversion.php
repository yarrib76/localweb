<?php

namespace Donatella\Http\Controllers\Contabilidad;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ReporteInversion extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function index()
    {
        return view('contabilidad.reporteinversion');
    }

    public function consulta()
    {
        $proveedores = Input::get();
        //Esta función se utiliza para poder hacer la consulta con la DB::
        function flatten_array($array) {
            $flatten = array();
            array_walk_recursive($array, function($a) use (&$flatten) { $flatten[] = $a; });
            return $flatten;
        }
        $flatten = flatten_array($proveedores);
        $quoted_elements = array_map(function($element){ return '"'.addslashes($element).'"'; }, $flatten);
        $string_coma = implode(",", $quoted_elements);

        // $result = DB::table('samira.reportearticulo')->whereIn('proveedor', $flatten)->get();
        $result = DB::select('SELECT Proveedor,
                    ROUND(SUM(CASE
                        WHEN (PrecioConvertido > 0 OR PrecioConvertido <> "") AND cantidad > 0 AND
                             (Proveedor = "LINDA MODA 2" AND PrecioOrigen < 10) THEN (cantidad * PrecioConvertido)
                        WHEN (PrecioConvertido > 0 OR PrecioConvertido <> "") AND cantidad > 0 AND
                             (Proveedor != "LINDA MODA 2") THEN (cantidad * PrecioConvertido)
                        ELSE (cantidad * PrecioManual)
                    END), 2) AS Total
                    FROM samira.articulos
                    WHERE Proveedor IN ('. $string_coma .')
                    GROUP BY Proveedor;');

        return Response::json($result);

        /* Utilizo este if para los proveedores que tienen los precios en USD mal cargados ejmplo LindaModa2
        *** Queda descontinuda este codigo ya que lo unifique como se obserba arriba***
        $string_sin_comillas = str_replace('"', '', $string_coma);
        if ($string_sin_comillas == 'LINDA MODA 2'){
            $result = DB::select('SELECT Proveedor,
                                ROUND(SUM(CASE
                                    WHEN (PrecioConvertido > 0 or PrecioConvertido <> "") and cantidad > 0 and PrecioOrigen < 10 THEN  (cantidad * PrecioConvertido)
                                    ELSE (cantidad * PrecioManual)
                                END),2) as Total
                                FROM samira.articulos
                                WHERE Proveedor IN ('. $string_coma .')
                                group by proveedor;');
        } else {
            $result = DB::select('SELECT Proveedor,
                                ROUND(SUM(CASE
                                    WHEN (PrecioConvertido > 0 or PrecioConvertido <> "") and cantidad > 0 THEN  (cantidad * PrecioConvertido)
                                    ELSE (cantidad * PrecioManual)
                                END),2) as Total
                                FROM samira.articulos
                                WHERE Proveedor IN ('. $string_coma .')
                                group by proveedor;');
        }
            return Response::json($result);
        */

    }
}
