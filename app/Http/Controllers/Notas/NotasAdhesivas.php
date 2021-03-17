<?php

namespace Donatella\Http\Controllers\Notas;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasAdhesivas extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user_id_roles = Auth::user()->id_roles;
        $notas = DB::Select('SELECT titulo,body,id_roles, tipo_role FROM samira.notas_adhesivas
                                inner join samira.rolesweb ON notas_adhesivas.id_rolesweb = rolesweb.id_roles
                                where id_roles = "'.$user_id_roles.'";');

        return view('notas.notasadhesivas',compact('notas'));
    }
}
