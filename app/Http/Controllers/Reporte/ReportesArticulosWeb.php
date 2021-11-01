<?php

namespace Donatella\Http\Controllers\Reporte;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ReportesArticulosWeb extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function getArticulosWeb()
    {
      //  $articulos = Articulos::where('Web', '=', 1)
      //      ->get();
      //  $articulosWeb = $this->queryFinal($articulos);
        return view('reporte.reportearticuloswebnew', compact('articulosWeb'));
        // return view('reporte.reportearticulosweb', compact('articulosWeb'));
    }

    public function queryFinal($articulos)
    {
        $precioAydua = new Precio();
        $query = [];
        $i = 0;
        foreach ($articulos as $articulo){
            $query [$i] = ['Articulo' => $articulo->Articulo,'Detalle' => $articulo->Detalle,
                'Precio' => $precioAydua->query($articulo)[0]['PrecioVenta'],
                'Stock' => $this->verificoStock($articulo),
                'WebSku'=> $articulo->websku];
            $i++;
        }
        return $query;
    }

    public function verificoStock($articulo)
    {
        if ($articulo->Cantidad >= 4){
            return "InStock";
        }
        return "OutOfStock";
    }

    public function query ()
    {
            $resultadoQuery = DB::select('select articulo, detalle, cantidad,
                                            CASE
                                                when web = 0 then "No"
                                                when web = 1 then "Si"
                                            END as sincronizar
                                          from samira.articulos');
        ob_start('ob_gzhandler');
        return Response::json($resultadoQuery);
    }

    public function update()
    {
        $datos = Input::all();
        if ($datos['sincronizar'] == "No"){
            DB::select ('UPDATE `samira`.`articulos`
                SET
                `Web` = 0
                where articulo = "'.$datos['articulo'].'" ');
        } else {
            DB::select ('UPDATE `samira`.`articulos`
                SET
                `Web` = 1
                where articulo = "'.$datos['articulo'].'" ');
        }
    }
}
