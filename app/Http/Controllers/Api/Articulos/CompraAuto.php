<?php

namespace Donatella\Http\Controllers\Api\Articulos;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
}
