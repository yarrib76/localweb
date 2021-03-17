<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\RoleWeb;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class RolesSelect extends Controller
{ public function query()
{
    $rolesWeb = RoleWeb::all();
    return Response::json($rolesWeb);
}
}
