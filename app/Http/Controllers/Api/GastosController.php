<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\RegistroGastos;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class GastosController extends Controller
{
    public function create(){
        RegistroGastos::create([
            'item_id' => Input::get('item_id'),
            'importe' => Input::get('importe'),
            'observaciones' => Input::get('observaciones'),
            'fecha' => Input::get('fecha')
        ]);
        return ['codigo' => 1];
    }

    public function listar(){
        $vendedores = RegistroGastos::where('id',20)->get();
        return $vendedores;
    }

    public function delete(){
        $gastos = RegistroGastos::find(Input::get('item_id'));
        $gastos->delete();
        return ['codigo' => 1];
    }

    public function listaGastosFecha(){
        $fecha = Input::get('fecha');
        $gastos = DB::select('SELECT * FROM samira.gastos
                              where fecha ="'.$fecha.'"');
        return Response::json($gastos);
    }
}
