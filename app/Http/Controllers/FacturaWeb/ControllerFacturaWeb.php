<?php

namespace Donatella\Http\Controllers\FacturaWeb;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
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
}
