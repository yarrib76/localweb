<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Proveedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ProveedoresSelect extends Controller
{
    public function query()
    {
        $proveedores = Proveedores::all();
        if (!is_null(Input::get('proveedor_name'))){
            $proveedores = Proveedores::where('Nombre', '=',Input::get('proveedor_name'))->get();
            return Response::json($proveedores);
        }
        return Response::json($proveedores);
    }
}
