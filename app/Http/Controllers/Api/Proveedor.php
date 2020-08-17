<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Proveedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Proveedor extends Controller
{
    public function getInfo()
    {
        $proveedor = Input::get('proveedor');
        $datosProveedor = Proveedores::where('nombre',$proveedor)->get();
        return Response::json($datosProveedor);
    }
}
