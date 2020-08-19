<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Donatella\Models\Clientes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ClientesArticulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cliente_id = Input::get("Cliente_ID");
        $año = Input::get("anio");
        $cliente = Clientes::where('id_clientes','=',$cliente_id)->get();
        $nombreCompleto = $cliente[0]->nombre . "," . $cliente[0]->apellido;
        DB::statement("SET lc_time_names = 'es_ES'");
        $clienteArticulos = DB::select('SELECT  factura.Articulo as Articulo, factura.Detalle as Descripcion, sum(factura.Cantidad) as Total
                            FROM samira.facturah as facth
                            INNER JOIN samira.factura as factura
                            ON facth.NroFactura = factura.NroFactura
                            where facth.id_clientes = "'. $cliente_id .'"
                            and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31"
                            GROUP BY factura.Articulo ORDER BY Total DESC;');
        return Response::json($clienteArticulos);
      //  return view('bi.clientearticulos', compact('clienteArticulos', 'año' , 'nombreCompleto'));
    }

    public function consultaArticulosByFactura()
    {
        $nroFactura = Input::get("nroFactura");
        $articulosByFactura = DB::select('SELECT  Articulo, Detalle as Descripcion, sum(Cantidad) as Total
                                            FROM samira.factura
                                            where nrofactura = "'. $nroFactura .'"
                                            GROUP BY Articulo ORDER BY Total DESC;');
        return Response::json($articulosByFactura);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
