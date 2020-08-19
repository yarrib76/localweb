<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class OutSincro extends Controller
{
    /*Esta Api se utiliza para enviar la lista de articulos 
      Es llamado dede la Api InSincro*/
    public function listaArticulos()
    {
        $autCode = Input::get('Codigo');
        if ($autCode == '3869')
        {
            $articulos = DB::select('SELECT Articulo,Detalle,Proveedor from samira.articulos;');
            return $articulos;
        }
        return Response::json('AutErro');
    }
}
