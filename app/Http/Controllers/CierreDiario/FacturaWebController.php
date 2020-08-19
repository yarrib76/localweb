<?php

namespace Donatella\Http\Controllers\CierreDiario;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class FacturaWebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::guest()){
            return View::make('/auth/login');
        } else {
            $fechaCierre = Input::get('fecha');
            $cierresDiarios = DB::select('SELECT NroFactura, Total, Porcentaje, Descuento, Ganancia, Fecha, (CASE WHEN Estado = 1 THEN "Caja Cerrada" ELSE  "Caja Abierta" END) as Estado,
                                      CONCAT (cli.nombre, "," , cli.apellido) as Cliente
                                      FROM samira.facturah as facth
                                      INNER JOIN samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                                      where Fecha = "' .$fechaCierre. '"');
            return view('cierrediario.reportefactura', compact('cierresDiarios','fechaCierre'));
        }
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
