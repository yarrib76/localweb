<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Dolar;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CotiDolar extends Controller
{
    public function query()
    {
        $dolar = Dolar::all();
        return Response::json($dolar);
    }
}
