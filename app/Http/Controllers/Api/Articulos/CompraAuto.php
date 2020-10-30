<?php

namespace Donatella\Http\Controllers\Api\Articulos;

use Donatella\Http\Controllers\Reporte\Articulo;
use Donatella\Models\Articulos;
use Donatella\Models\CompraAutoDB;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CompraAuto extends Controller
{
    public function consulta()
    {
        $articulos = DB::Select ('SELECT Arti.Articulo as Articulo, Arti.Detalle as Detalle, Arti.ProveedorSKU as ProveedorSKU, Arti.Cantidad as Cantidad,
                                    sum(if(Control.estado = 1, pedidotemp.Cantidad,0)) as Pedido, repoArt.PrecioVenta as PrecioVenta, Arti.ImageName, Arti.Web, "Accion"
                                    FROM samira.articulos as Arti
                                    left join samira.pedidotemp as pedidoTemp On Arti.Articulo = pedidoTemp.Articulo
                                    inner join samira.reportearticulo as repoArt On Arti.Articulo = repoArt.Articulo
                                    left join samira.controlpedidos as Control ON pedidotemp.NroPedido = Control.nropedido
                                    where Arti.CompraAuto = 0
                                    group by Arti.Articulo');
        ob_start('ob_gzhandler');
        return Response::json($articulos);
    }

    public function llenarTablaTabulador()
    {
        $articulos = DB::select('select compraAuto.articulo as Articulo, arti.Detalle as Detalle, cant_alerta from samira.compraautomatica as compraAuto
                                 inner join samira.articulos as arti on compraAuto.articulo = arti.articulo');
        ob_start('ob_gzhandler');
        return Response::json($articulos);
    }
    public function agregarArticulo()
    {
        $nroArticulo = Input::get('nroArticulo');
        $articulo = Articulos::where('articulo', $nroArticulo);
        CompraAutoDB::create([
            'articulo' => $nroArticulo
        ]);
        $articulo->update([
            'CompraAuto' => 1
        ]);
    }

    public function editarUmbralAlerta()
    {
        $articulo = Input::get('Articulo');
        $umbralAlerta = Input::get('cant_alerta');
        DB::select('update samira.compraAutomatica set cant_alerta   = "'. $umbralAlerta . '" where articulo = "'. $articulo .'"');
    }

    public function eliminarArticulo()
    {
        $articulo = Input::get('Articulo');
        DB::select('delete from samira.compraAutomatica where articulo = "'. $articulo .'"');
        DB::select('update samira.articulos  set CompraAuto   = 0 where articulo = "'. $articulo .'"');
    }
}