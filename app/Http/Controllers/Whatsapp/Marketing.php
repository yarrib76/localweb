<?php

namespace Donatella\Http\Controllers\Whatsapp;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Marketing extends Controller
{
    public function index()
    {
        return view('whatsapp.reporte');
    }
}
