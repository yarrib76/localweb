<?php

namespace Donatella\Http\Controllers\Contabilidad\Financiera;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class Prestigio extends Controller
{
    public function getExcel()
    {
        return view('contabilidad/financiera/importexport');
    }
    public function importExcel(Request $request)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            $path = '/public/importacion';
            $excelFile = Input::file('import_file')->getClientOriginalName();
            $this->muevoArchivosImages($excelFile, $path);
            $path = $path . "/" . $excelFile;
            $data = Excel::selectSheets('BD')->load($path, function ($reader) {
                // No necesitas hacer nada aquí si solo estás cargando la hoja
            })->get();

            // Reinicia los índices y selecciona el rango deseado
            $desde = 4646 - 2;
            $filteredRows = $data->values()->slice($desde, 26); // Desde la fila 4712, leer 40 filas (4751-4712)
            dd($filteredRows->toArray());
        }
    }


    public function muevoArchivosImages($excelFile, $path)
    {
        if (Input::file('import_file')) {
            //  $imageName1 = Input::get('cod_articulo') . "1" . Carbon::now()->toTimeString() . "." . Input::file('image_name_1')->getClientOriginalExtension();
            Input::file('import_file')->move(
                base_path() . $path, $excelFile);

        }
    }
}
