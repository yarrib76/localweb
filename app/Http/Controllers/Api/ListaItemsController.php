<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Items;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ListaItemsController extends Controller
{
    public function query(){
      //  $listaItems = Items::all();
      //  $listaItems = $this->totalesPorItems($listaItems);
        $listaItemsTotal = new Items();
        $anio = Input::get('anio');
        $mes = Input::get('mes');
        $call = Input::get('call');
        if ($call == "full"){
            return Response::json($listaItemsTotal->itemsTotalAnioMes($anio,$mes));
        }
        return Response::json($listaItemsTotal->itemsTotal());
    }

}
