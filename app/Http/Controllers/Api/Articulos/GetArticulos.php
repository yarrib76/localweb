<?php

namespace Donatella\Http\Controllers\Api\Articulos;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class GetArticulos extends Controller
{
    public function consulta()
    {
        /*
        $articulos = DB::Select ('SELECT Arti.Articulo as Articulo, Arti.Detalle as Detalle, Arti.ProveedorSKU as ProveedorSKU, Arti.Cantidad as Cantidad,
                                    sum(if(Control.estado = 1, pedidotemp.Cantidad,0)) as Pedido, repoArt.PrecioVenta as PrecioVenta, Arti.ImageName, Arti.Web, "Accion"
                                    FROM samira.articulos as Arti
                                    left join samira.pedidotemp as pedidoTemp On Arti.Articulo = pedidoTemp.Articulo
                                    inner join samira.reportearticulo as repoArt On Arti.Articulo = repoArt.Articulo
                                    left join samira.controlpedidos as Control ON pedidotemp.NroPedido = Control.nropedido
                                    group by Arti.Articulo');
        */
        $articulos = DB::select (' SELECT art.Articulo AS Articulo,
                                    art.Detalle AS Detalle,
                                    art.ProveedorSKU AS ProveedorSKU,
                                    COALESCE(art.Cantidad, 0) AS Cantidad,
                                    COALESCE(ped.Pedido, 0) AS Pedido,
                                    repoArt.PrecioVenta AS PrecioVenta,
                                    art.ImageName,
                                    art.Web,
                                    "Accion" AS Accion
                                  FROM samira.articulos AS art
                                  INNER JOIN samira.reportearticulo AS repoArt ON art.Articulo = repoArt.Articulo
                                  LEFT JOIN (
                                    SELECT
                                      pt.Articulo,
                                      SUM(pt.Cantidad) AS Pedido
                                    FROM samira.pedidotemp pt
                                    INNER JOIN samira.controlpedidos cp ON pt.NroPedido = cp.nropedido
                                    WHERE cp.estado = 1
                                    GROUP BY pt.Articulo
                                  ) ped ON ped.Articulo = art.Articulo
                                  ORDER BY art.Articulo');
        ob_start('ob_gzhandler');
        return Response::json($articulos);
    }

    public function foto()
    {
        $nroArticulo = Input::get('nroArticulo');
        $imagessrc = DB::select ('SELECT imagessrc FROM samira.statusecomercesincro as StatusSincr
                                    where articulo = "'.$nroArticulo.'"
                                    and StatusSincr.id_provecomerce = (select id_provecomerce from samira.statusecomercesincro
                                    order by id_provecomerce Desc limit 1);');
        return Response::json($imagessrc);
    }
}
