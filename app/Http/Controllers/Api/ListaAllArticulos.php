<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ListaAllArticulos extends Controller
{
    public function query()
    {
        if (Input::get('nroArticulo')){
            $precioVenta = new Precio();
            $articulo = Articulos::where('Articulo','=',Input::get('nroArticulo'))->get();
            $json = ['Articulo' => $articulo[0]->Articulo,'Detalle' => $articulo[0]->Detalle,
                'Cantidad' => $articulo[0]->Cantidad, 'PrecioVenta' => $precioVenta->query($articulo[0])[0]['PrecioVenta']];
            return Response::json($articulo);
        }
        $articulos = Articulos::all();
        return Response::json($articulos);
    }
}
