<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Articulos;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Sku extends Controller
{
    public function query()
    {
        $nroArticulo = Input::get('nroarticulo');
        $webSku = Input::get('webSku');
        $this->modificarSku($nroArticulo,$webSku);
        $articulo = Articulos::where('Articulo', '=', $nroArticulo)->get();
        return Response::json ($articulo);
    }

    public function modificarSku($nroArticulo,$webSku)
    {
        if ($webSku == 0){
            $webSku = null;
        }
        DB::table('articulos')
            ->where('articulo', $nroArticulo)
            ->update(['websku' => $webSku]);
    }
}
