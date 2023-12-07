<?php

namespace Donatella\Http\Controllers\FacturaWeb;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Tipo_Pagos;
use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ControllerFacturaWeb extends Controller
{
    public function view()
    {
        return view('facturaweb.factmenuprincipal');
    }

    public function getArticulos()
    {
        $articulos = DB::select('select Articulo, Detalle, Cantidad from samira.articulos');

        return Response::json($articulos);
    }

    public function precioArticulo()
    {
        $nroArticulo = Input::get('nroArticulo');
        $articulo = Articulos::where('Articulo', '=', $nroArticulo)->get();
        $precio = new Precio();
        $precio = $precio->query($articulo[0]);
        return $precio;
    }

    public function listaVendedoras(){
        $vendedoras = Vendedores::where('tipo', '<>', '0')->get(); //Las que tienen tipo 0 estan desabilitadas
        return Response::json($vendedoras);
    }

    public function listaTipoPagos(){
        $tipo_pagos = Tipo_Pagos::all();
        return Response::json($tipo_pagos);
    }

    public function getClientes(){
        $clientes = DB::select('select id_clientes, nombre, apellido, mail from samira.clientes;');
        return Response::json($clientes);
    }
}
