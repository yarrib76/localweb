<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\ComentariosPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ListaComentariosWeb extends Controller
{
    public function query()
    {
        $controlPedido = Input::get('controlpedidos_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $comentarios = DB::select('SELECT DATE_FORMAT(fecha, "%d de %M %Y %k:%i") AS fechaFormateada, usuarios.name as nombre,
                            comentPedidos.comentario as comentario
                            from samira.comentariospedidos comentPedidos
                            INNER JOIN samira.users as usuarios ON usuarios.id = comentPedidos.users_id
                            WHERE comentPedidos.controlpedidos_id = "'. $controlPedido . '"
                            ORDER BY fecha DESC');
        return Response::json($comentarios);
    }
}
