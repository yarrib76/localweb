<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class Login extends Controller
{
    public function authentic()
    {
        $usuario = Vendedores::where('Nombre', Input::get('usuario'))->first();
        $password = Input::get('password');
        $authenticate = $this->validarLogin($usuario,$password);
        return $authenticate;
    }

    public function crearLogin()
    {
        $this->crearUsuario();
    }
    private function crearUsuario()
    {
        $password = '3869';
        $passwordEncrypt = $this->encrypPassword($password);
        Vendedores::create([
            'Nombre' => 'cel',
            'Apellido' => 'cel',
            'Tipo' => 1,
            'Password' => $passwordEncrypt
        ]);
    }

    private function encrypPassword($password)
    {
        $passwordEncrypt = Hash::make(($password));
        return $passwordEncrypt;
    }

    private function validarLogin($usuario,$password)
    {
        if (Hash::check($password, $usuario->Password)){

            return [ 'valor' => 1];
        }else{
            return [ 'valor' => 0];
        }
    }
}
