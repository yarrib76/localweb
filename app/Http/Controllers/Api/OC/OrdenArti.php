<?php

namespace Donatella\Http\Controllers\Api\OC;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class OrdenArti extends Controller
{
    public function inTn()
    {
        $nroPedido = Input::get('nroPedido');
        $artEnTiendaNube = DB::Select('SELECT controlpedidos.nropedido, OrdenWeb, OrdenArti.articulo, OrdenArti.detalle, OrdenArti.cantidad, OrdenArti.precio, Arti.cantidad as stock
                                        FROM samira.controlpedidos as controlPedidos
                                        inner join samira.ordenesarticulos as OrdenArti ON OrdenArti.id_controlPedidos = controlpedidos.id
                                        inner join samira.articulos as Arti ON OrdenArti.articulo = Arti.Articulo
                                        left join samira.pedidotemp as ptemp ON OrdenArti.articulo = ptemp.articulo
                                        and ptemp.NroPedido = controlpedidos.nropedido
                                        where controlpedidos.nropedido = "'. $nroPedido .'"
                                        and ptemp.Articulo is null;');
        return Response::json($artEnTiendaNube);
    }

    public function inLocalSystem()
    {
        $nroPedido = Input::get('nroPedido');
        $artEnSistemaLocal = DB::select('SELECT ptemp.nropedido, OrdenWeb, ptemp.Articulo, ptemp.detalle, ptemp.cantidad, ptemp.PrecioVenta
                                            FROM samira.controlpedidos as controlPedidos
                                            inner join samira.pedidotemp as ptemp ON ptemp.NroPedido = controlpedidos.nropedido
                                            left join samira.ordenesarticulos as OrdenArti ON OrdenArti.articulo = ptemp.Articulo
                                            and OrdenArti.id_controlPedidos = controlpedidos.id
                                            where controlpedidos.nropedido = "'. $nroPedido .'"
                                            and OrdenArti.Articulo is null;');
        return $artEnSistemaLocal;
    }

    public function inDiff()
    {
        $nroPedido = Input::get('nroPedido');
        $artDiffEnLocalyTN = DB::select('select controlpedidos.nropedido, OrdenArti.articulo, OrdenArti.detalle, sum(OrdenArti.cantidad) as TNCantidad, OrdenArti.precio as TNPrecio,
                                            pedidotemp.cantidad as CantidadLocal, pedidotemp.PrecioUnitario as PrecioLocal
                                            from samira.controlpedidos
                                            inner join samira.pedidotemp ON pedidotemp.nropedido = controlpedidos.nropedido
                                            inner join samira.ordenesarticulos as OrdenArti ON OrdenArti.articulo = pedidotemp.articulo
                                            and OrdenArti.id_controlPedidos = controlpedidos.id
                                            where controlpedidos.nropedido = "'. $nroPedido .'"
                                            group by OrdenArti.articulo
                                            having TNCantidad <> CantidadLocal or TNPrecio <> PrecioLocal;');
        return $artDiffEnLocalyTN;
    }
}
