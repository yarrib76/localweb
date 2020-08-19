<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;
use TiendaNube\Auth;

class ArtTiendaNube extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function index()
    {
        $store_id = Input::get('store');
        return view('tiendanube.sincronizacionarticulos',compact('store_id'));
    }
}
