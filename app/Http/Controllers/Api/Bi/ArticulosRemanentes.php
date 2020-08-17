<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ArticulosRemanentes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function index()
    {

        return view('bi.artremanentes', compact('pedidos','user_id'));

    }
    public function query()
    {
        $fecha = Input::get('fecha');
        $maxVendido = Input::get('maxVendidos');
        $minStock = Input::get(('minStock'));
        $i= 0;
        $remanentes [] = "";
        $articulos = DB::select('SELECT fact.Articulo as Articulo, arti.detalle as Detalle , sum(fact.Cantidad) as Vendidos,
                                arti.Cantidad as Total
                                FROM samira.factura as fact
                                INNER JOIN samira.articulos as arti ON arti.Articulo = fact.Articulo
                                WHERE fact.Fecha >= "'.$fecha.'" and arti.Cantidad >= "'.$minStock.'"
                                GROUP BY fact.Articulo
                                HAVING Vendidos < "'.$maxVendido.'";');
        foreach ($articulos as $articulo){
            $compra = DB::select ('select sum(Cantidad) as Cantidad
                                  FROM samira.compras
                                  WHERE FechaCompra >= "'.$fecha.'" and Articulo = "'.$articulo->Articulo.'" and TipoOrden = 2 ');
            if (is_null($compra[0]->Cantidad)){
                $remanentes[$i] = ['Articulo' => $articulo->Articulo,'Detalle' => $articulo->Detalle, 'Vendidos' => $articulo->Vendidos,
                                  'Stock' => $articulo->Total,'Comprados' => 0];
            }else {
                $remanentes[$i] = ['Articulo' => $articulo->Articulo,'Detalle' => $articulo->Detalle, 'Vendidos' => $articulo->Vendidos,
                    'Stock' => $articulo->Total,'Comprados' => $compra[0]->Cantidad];
            }
            $i++;
        }
        return Response::json($remanentes);
    }
}
