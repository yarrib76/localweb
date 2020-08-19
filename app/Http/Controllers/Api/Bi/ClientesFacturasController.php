<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Illuminate\Http\Request;
use Donatella\Models\Clientes;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ClientesFacturasController extends Controller
{
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
        $clienteFacturas = DB::select('SELECT  Nrofactura, Total, DATE_FORMAT(fecha, "%d de %M %Y") AS Fecha
                            FROM samira.facturah as facth
                            where facth.id_clientes = "'. $cliente_id .'"
                            and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31";');
        return Response::json($clienteFacturas);
      //  return view('bi.clientefacturas', compact('clienteFacturas', 'año' , 'nombreCompleto'));
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
