<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Models\Articulos;
use Donatella\Models\Proveedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Editar extends Controller
{
    //hola mundo
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index()
    {
        $proveedores =Proveedores::all();
        return view('articulos.editgeneral',compact('proveedores'));
    }

    public function query()
    {
        $proveedor = Input::get('Proveedor');
        $articulos = Articulos::where('Proveedor', $proveedor)->get();
        return Response::json($articulos);
    }

    public function update(){
        $dato =  Input::all();
        $articulo = Articulos::where('Articulo', $dato['Articulo']);
        $articulo->update([
            'PrecioConvertido' => $dato['PrecioConvertido'],
            'PrecioOrigen' => $dato['PrecioOrigen'],
            'PrecioManual' => $dato['PrecioManual'],
            'Gastos' => $dato['Gastos'],
            'Ganancia' => $dato['Ganancia']
        ]);
        return $dato;
    }
}
