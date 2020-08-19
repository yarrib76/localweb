<?php

namespace Donatella\Http\Requests;

use Donatella\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ClientesRequestForm extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::guest()){
            return View::make('/auth/login');
        } else {
            return \Auth::check();
        }
       // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Mail' => 'unique:clientes,mail,'. $this->get('id') .',id_clientes'
        ];
    }

    public function messages()
    {
        return [
            'Mail.unique' => 'El mail ya existe en el sistema',
        ];
    }


}
