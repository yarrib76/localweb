<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\NroPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GeneraNroPedidos extends Controller
{
    public function Generar()
    {
        $getNroPedido = NroPedidos::all();
        $nroPedido = $getNroPedido[0]->Nropedido + 1;
        DB::table('nropedido')->update(array('Nropedido' => $nroPedido));
        $nropedidoFinal = ['nroPedido' => $nroPedido];
        return $nropedidoFinal;
    }
}
