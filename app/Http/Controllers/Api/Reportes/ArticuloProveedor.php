<?php

namespace Donatella\Http\Controllers\Api\Reportes;

use Donatella\Models\ReporteArtiulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ArticuloProveedor extends Controller
{
    public function query()
    {
        $articulosProveedor = ReporteArtiulos::all();
        ob_start('ob_gzhandler');
        return Response::json($articulosProveedor);
    }
}
