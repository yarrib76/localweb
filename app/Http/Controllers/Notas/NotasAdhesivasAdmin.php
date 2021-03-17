<?php

namespace Donatella\Http\Controllers\Notas;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Donatella\Models\Notas_Adhesivas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class NotasAdhesivasAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notas = DB::Select('SELECT id_notas_adhesivas,titulo,body,tipo_role as tipo_rol FROM samira.notas_adhesivas
                              inner join samira.rolesweb ON notas_adhesivas.id_rolesweb = rolesweb.id_roles;');
        return view('notas.reporte', compact('notas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('notas.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::Select ('INSERT INTO samira.notas_adhesivas
                        (titulo,body,id_rolesweb)
                        VALUES
                        ( "'.Input::get('Titulo').'",
                         "'.Input::get('body').'",
                         "'.Input::get('id_rolesweb').'");');

        return redirect()->route('notasadmin.index');
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
        $nota = DB::Select('SELECT id_notas_adhesivas,titulo,body,id_roles,tipo_role FROM samira.notas_adhesivas
                                inner join samira.rolesweb ON notas_adhesivas.id_rolesweb = rolesweb.id_roles
                                where id_notas_adhesivas = "'.$id.'"; ');
        $nota = $nota[0];
        // dd($cliente['provincias']->nombre);
        return view('notas.edit', compact('nota'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nota= Notas_Adhesivas::where('id_notas_adhesivas', $id);
        $nota->update([
            'titulo' => Input::get('Titulo'),
            'body' => Input::get('body'),
            'id_rolesweb' => Input::get('id_rolesweb'),
        ]);

        return redirect()->route('notasadmin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notas_Adhesivas::where('id_notas_adhesivas', $id)->delete();
        return redirect()->route('notasadmin.index');
    }
}
