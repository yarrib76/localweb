<?php

namespace Donatella\Http\Requests;

use Donatella\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class PromocionRequestForm extends Request
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
           'Cliente_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Cliente_id.required' => 'Debe Cargar un Cliente',
        ];
    }


}
