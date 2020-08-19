<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ListaRegistroLlamadasWeb extends Controller
{
    public function query()
    {
        $cliente_id = Input::get('cliente_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $comentarios = DB::select('SELECT DATE_FORMAT(fecha, "%d de %M %Y %k:%i") AS fecha, usuarios.name as nombre,
                            regllamadas.comentarios as comentario
                            from samira.registrollamadas regllamadas
                            INNER JOIN samira.users as usuarios ON usuarios.id = regllamadas.users_id
                            WHERE regllamadas.clientes_id = "'. $cliente_id . '"
                            ORDER BY fecha DESC');
        return Response::json($comentarios);
    }
}
