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
        $usuarios = DB::select('SELECT users.id, name,email,codigo, rolesweb.tipo_role as rol, foto FROM samira.users
                            inner join samira.rolesweb on rolesweb.id_roles = users.id_roles;');
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
        $fotoPersonal = Input::get('fotoPersonal');

        DB::select('UPDATE samira.users SET name = "'.$nombre.'", email = "'.$email.'", codigo = "'.$codigo.'",
                    foto = "'.$fotoPersonal.'"
                    where id = "'.$user_id.'"');
        return Response::json("OK");;
    }
}
