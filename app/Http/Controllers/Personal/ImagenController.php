<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;


class ImagenController extends Controller
{
    public function guardar(Request $request)
    {

        // Obtiene el archivo cargado
        $archivo = $request->file('imagen');

        // Genera un nombre único para el archivo
        $nombreArchivo = uniqid('imagen_') . '.' . $archivo->getClientOriginalExtension();
        // Mueve el archivo cargado a la carpeta 'imagenes' en el directorio de almacenamiento
        $archivo->move(public_path('imagenes'), $nombreArchivo);

        return Response::json($nombreArchivo);
    }

}


