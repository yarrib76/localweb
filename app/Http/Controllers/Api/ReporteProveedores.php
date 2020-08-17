<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Proveedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class ReporteProveedores extends Controller
{
    public function getProveedores()
    {
        $query = Proveedores::selectRaw('Nombre')
            ->orderBy('Nombre', 'ASC')
            ->get();
        return $query;
    }
}
