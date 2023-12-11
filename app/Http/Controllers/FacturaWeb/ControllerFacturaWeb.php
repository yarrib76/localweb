<?php

namespace Donatella\Http\Controllers\FacturaWeb;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Tipo_Pagos;
use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ControllerFacturaWeb extends Controller
{
    public function view()
    {
        $nameCajera = Auth::user()->name;
        return view('facturaweb.factmenuprincipal', compact('nameCajera'));
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

    public function facturar(){
        $datosFactura = (Input::get('articulos'));
        $datosFactura = json_decode($datosFactura);

        foreach ($datosFactura as $dato) {
            // Hacer algo con cada dato (por ejemplo, imprimirlo)
            dump($dato);
        }
        $cliente_id = Input::get('cliente_id');
        $tipo_pago_id = Input::get('tipo_pago_id');
        dump($cliente_id,$tipo_pago_id);
    }
}
