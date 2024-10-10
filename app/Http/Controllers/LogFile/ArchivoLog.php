<?php

namespace Donatella\Http\Controllers\LogFile;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ArchivoLog extends Controller
{
    public function saveLog()
    {
        $error = Input::get('error');
        $fileName = Input::get('fileName');
        // Definir la ruta del archivo .log
        $logFile = storage_path('logs/' . $fileName . '.log');
        // $logFile = storage_path('logs/mi_archivo.log');
        // Definir el mensaje a registrar
        $mensaje = "[" . date('Y-m-d H:i:s') . "]" . $error . "\n";
        // Guardar el mensaje en el archivo
        file_put_contents($logFile, $mensaje, FILE_APPEND);
        // FILE_APPEND asegura que el contenido se agregue al archivo, en lugar de sobrescribirlo.
    }
}
