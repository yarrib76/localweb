<?php

namespace Donatella\Http\Controllers\Personal;

use Donatella\Ayuda\CodigoBarras;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AbmPersonal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index()
    {
        $usuarios = DB::select('SELECT users.id, name,email,codigo, rolesweb.tipo_role as rol, foto, vendedores.nombre as vendedora FROM samira.users
                            inner join samira.rolesweb on rolesweb.id_roles = users.id_roles
                            inner join samira.vendedores on vendedores.id = users.id_vendedoras;');
        return view ('personal.reporte',compact('usuarios'));
    }

    public function obtngoCodigoBarra()
    {
        $codigoPais = '7799';
        $codigoBarra = new CodigoBarras();
        $articulo = $codigoPais . Input::get('codigo');
        $codigoBit = $codigoBarra->crearDigitoCOntrol($articulo);
        $articulo = $articulo . $codigoBit;
        return($articulo);
    }

    public function guardar()
    {
        $nombre = Input::get('nombre');
        $email = Input::get('email');
        $codigo = Input::get('codigo');
        $user_id = Input::get('user_id');
        $tipo_rol = Input::get('tipo_role');
        $fotoPersonal = Input::get('fotoPersonal');
        $vendedora = Input::get('vendedora');
        $id_role = DB::select('select id_roles from samira.rolesweb where tipo_role="'.$tipo_rol.'" ');
        $id_vendedora = DB::select('select id from samira.vendedores where nombre ="'.$vendedora.'"');
        DB::select('UPDATE samira.users SET name = "'.$nombre.'", email = "'.$email.'", codigo = "'.$codigo.'",
                    foto = "'.$fotoPersonal.'", id_roles = "'.$id_role[0]->id_roles.'", id_vendedoras = "'.$id_vendedora[0]->id.'"
                    where id = "'.$user_id.'"');
        return Response::json("OK");;
    }
}
