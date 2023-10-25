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
                                    WHEN PrecioConvertido > 0 or PrecioConvertido <> "" THEN  (cantidad * PrecioConvertido)
                                    ELSE (cantidad * PrecioManual)
                                END),2) as Total
                                FROM samira.articulos
                                WHERE Proveedor IN ('. $string_coma .')
                                group by proveedor;');

        return Response::json($result);
    }
}
