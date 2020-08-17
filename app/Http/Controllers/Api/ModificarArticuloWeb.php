<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class ModificarArticuloWeb extends Controller
{
    public function modifico()
    {
        $nroArticulo = Input::all();
        $articulo = Articulos::where('Articulo', $nroArticulo)->get();
        if ($articulo[0]->Web == 0){
            Articulos::where('Articulo', '=', $nroArticulo)->update([
                'Web' => 1,
            ]);
            $articulo = Articulos::where('Articulo', $nroArticulo)->get();
            return ($articulo[0]->Web);
        }
        Articulos::where('Articulo', '=', $nroArticulo)->update([
            'Web' => 0,
        ]);
        $articulo = Articulos::where('Articulo', $nroArticulo)->get();
        return ($articulo[0]->Web);
    }
}
