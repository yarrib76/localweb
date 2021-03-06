<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ListaVendedoras extends Controller
{
    public function query()
    {
        $vendedoras = Vendedores::all();
        return Response::json($vendedoras);
    }
}
