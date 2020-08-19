<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ArticulosClientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function query()
    {
        if (Input::get('articulo')){
            $datos = $this->artcli(Input::get('articulo'));
            return Response::json($datos);
        }
        $datos = $this->articulos();
        return view('bi.articulocliente', compact('datos'));
    }

    public function articulos()
    {
        $articulos = DB::select('SELECT art.Articulo as Articulo, art.Detalle as Detalle, sum(fact.Cantidad) as Cantidad
                                FROM samira.articulos as art
                                INNER JOIN samira.factura as fact ON art.Articulo = fact.Articulo
                                INNER JOIN samira.facturah as facth ON facth.NroFactura = fact.NroFactura
                                INNER join samira.clientes as cli ON facth.id_clientes = cli.id_clientes
                                WHERE cli.id_clientes <> 1
                                GROUP BY Articulo');
        return $articulos;
    }
    public function artcli($articulo)
    {
        $artcli = DB::select('SELECT fact.Articulo as Articulo, fact.Detalle as Detalle,
                            CONCAT (cli.nombre, "," , cli.apellido) as Cliente, sum(fact.Cantidad) as Cantidad
                            FROM samira.factura as fact
                            INNER JOIN samira.facturah as facth ON facth.NroFactura = fact.NroFactura
                            INNER JOIN samira.clientes as cli ON facth.id_clientes = cli.id_clientes
                            WHERE fact.Articulo = "'.$articulo.'" and cli.id_clientes <> 1
                            GROUP BY cliente ORDER BY Cantidad desc;');
        return $artcli;
    }
}
