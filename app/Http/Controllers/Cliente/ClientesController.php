<?php

namespace Donatella\Http\Controllers\Cliente;

use Donatella\Http\Requests\ClientesRequestForm;
use Donatella\Models\Clientes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    /*
        $clientes = Clientes::where('id_clientes', '<>', 1)
            ->orderby('nombre', 'desc')
            ->get();
      //  dd($clientes[0]);
    */
        $user_id = Auth::user()->id;
        return view('clientes.reporte2', compact('clientes','user_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientesRequestForm $request)
    {
        Clientes::create([
            "Nombre" => Input::get('Nombre'),
            "Apellido" => Input::get('Apellido'),
            "Apodo" => Input::get('Apodo'),
            "Direccion" => Input::get('Direccion'),
            "Mail" => Input::get('Mail'),
            "Telefono" => Input::get('Telefono'),
            "Cuit" => Input::get('Cuit'),
            "Localidad" => Input::get('Localidad'),
            "Provincia" => Input::get('Provincia'),
            "CodigoPostal" => Input::get('codigo_postal'),
            "Id_provincia" => Input::get('Provincia_id'),
            'encuesta' => Input::get('Encuesta'),
        ]);

        return redirect()->route('clientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Clientes::where('id_clientes', '=', $id)->get()->load('provincias');
        $cliente = $cliente[0];
        // dd($cliente['provincias']->nombre);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientesRequestForm $request, $id)
    {
        $cliente= Clientes::where('id_clientes', $id);
        $cliente->update([
            'Nombre' => Input::get('Nombre'),
            'Apellido' => Input::get('Apellido'),
            'Apodo' => Input::get('Apodo'),
            'Cuit' => Input::get('Cuit'),
            'Direccion' => Input::get('Direccion'),
            'Localidad' => Input::get('Localidad'),
            'Provincia' => Input::get('Provincia'),
            "CodigoPostal" => Input::get('codigo_postal'),
            "Id_provincia" => Input::get('Provincia_id'),
            'Mail' => Input::get('Mail'),
            'Telefono' => Input::get('Telefono'),
            'encuesta' => Input::get('Encuesta'),
        ]);

       return redirect()->route('clientes.index');
     //   return Redirect::back()->with('code', 5);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
