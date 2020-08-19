<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Articulos;
use Donatella\Models\Facturas;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ReporteArticulos extends Controller
{
    public function masVendidos()
    {
        $añoDesde = Input::get('anioDesde');
        $añoHasta = Input::get('anioHasta');
        $proveedor = Input::get('proveedor');
        $query = $this->queryGen($añoDesde, $añoHasta, $proveedor);
        return Response::json($query);
    }

    public function queryGen($añoDesde, $añoHasta, $proveedor)
    {
        /* Ojo puede que este limitado a una cantidad de registros */
        if ($proveedor == "SinFiltro"){
            $query = DB::select('SELECT fac.Articulo, art.Detalle, SUM(fac.cantidad) AS TotalVendido,  art.Cantidad AS TotalStock,
                    art.ImageName, repoArt.PrecioVenta
                    FROM samira.factura AS fac JOIN samira.articulos AS art ON fac.Articulo = art.Articulo
                    JOIN samira.reportearticulo AS repoArt ON fac.Articulo = repoArt.Articulo
                    WHERE fac.Fecha >= "' . $añoDesde . '" and fac.Fecha <= "' . $añoHasta . '" and fac.Estado <> 2
                    GROUP BY fac.Articulo
                    ORDER BY TotalVendido DESC;');
        }else {
            $query = DB::select('SELECT fac.Articulo, art.Detalle, SUM(fac.cantidad) AS TotalVendido,  art.Cantidad AS TotalStock, art.ImageName,
                    repoArt.PrecioVenta
                    FROM samira.factura AS fac JOIN samira.articulos AS art ON fac.Articulo = art.Articulo
                    JOIN samira.reportearticulo AS repoArt ON fac.Articulo = repoArt.Articulo
                    WHERE fac.Fecha >= "' . $añoDesde . '" and fac.Fecha <= "' . $añoHasta . '" and fac.Estado <> 2
                    and art.Proveedor = "' . $proveedor . '"
                    GROUP BY fac.Articulo
                    ORDER BY TotalVendido DESC;');
        }

        return $query;
    }


    /* Agrego Busqueda en Tabla ReporteArticulos
    $query = DB::select('SELECT fac.Articulo, art.Detalle, SUM(fac.cantidad) AS TotalVendido,  art.Cantidad AS TotalStock,
    art.ImageName, repoArt.PrecioVenta
    FROM samira.factura AS fac JOIN samira.articulos AS art ON fac.Articulo = art.Articulo
    JOIN samira.reportearticulo AS repoArt ON art.Articulo = repoArt.Articulo
    WHERE fac.Fecha >= "' . $añoDesde . '" and fac.Fecha <= "' . $añoHasta . '" and fac.Estado <> 2
    GROUP BY fac.Articulo
    ORDER BY TotalVendido DESC;'); */

    /* Estaba en funcion Stock (Obsoleto) ********************************
    $articulosVendidos = Facturas::groupBy('Articulo')
            ->selectRaw('Articulo, Fecha, Detalle, sum(Cantidad) as Cantidad')
            ->where ('Fecha', '>=', $añoDesde)
            ->where ('Fecha', '<=', $añoHasta)
            ->orderBy('Cantidad', 'DESC')
            ->get();
        $stockArticulos = $this->stock();
        $query = $this->reporteFinal($stockArticulos,$articulosVendidos);

        return Response::json($query);
    public function stock()
    {
        // $query = Facturas::where('Fecha', '>', '2015-01-01')->get();
        $query = articulos::groupBy('Articulo')
            ->selectRaw('sum(Cantidad) as Cantidad, Articulo')
            ->orderBy('Cantidad', 'DESC')
            ->get();
        return $query;
    }

    private function reporteFinal($stockArticulos, $articulosVendidos)
    {
        $i = 0;
        foreach ($articulosVendidos as $articulosVendido) {
            $articulo = $stockArticulos->where('Articulo', $articulosVendido->Articulo)->first();
            if (!empty($articulo)) {
                $datos[$i] = ['Articulo' => $articulosVendido->Articulo,
                    'Detalle' => $articulosVendido->Detalle,
                    'TotalVendido' => $articulosVendido->Cantidad,
                    'TotalStock' => $articulo->Cantidad];
            }
            $i++;
        }
        return $datos;
    }

    */
}
