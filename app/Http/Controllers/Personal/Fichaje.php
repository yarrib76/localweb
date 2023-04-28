<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Fichaje extends Controller
{
    public function index()
    {
        return view ('personal.fichaje');
    }
}
