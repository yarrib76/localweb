<?php

namespace Donatella\Http\Controllers\CorreoArgentino;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ImportSucFromCSV extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function indexImport()
    {
        return view('correoargentino/importsucfromcsv');
    }

    public function importCsv(Request $request)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            $path = '/public/importacion';
            $imageName1 = Input::file('import_file')->getClientOriginalName();
            $this->muevoArchivosImages($imageName1, $path);
            $path = $path . "/" . $imageName1;
            $data = Excel::load($path, function ($reader) {
            })->get();
            $this->truncoTabla();
            if (!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    $insert[] = ['codigo_provincia' => strval($value['codigo_provincia']), 'nombre_provincia' => $value['nombre_provincia'],
                        'codigo_sucursal' => $value['codigo_sucursal'], 'nombre_sucursal' => $value['nombre_sucursal']];
                }
                if (!empty($insert)) {
                    foreach ($insert as $data) {
                        $id_provincia = DB::select('select id from samira.provincias
                                                where nombre = "'.$data['nombre_provincia'].'"');
                        DB::select('INSERT INTO samira.pub_sucursales (id_provincias, codigo_provincia, codigo_sucursal, nombre_sucursal)
                                    VALUES ("' . $id_provincia[0]->id . '", "' . $data['codigo_provincia'] . '","'.$data['codigo_sucursal'].'",
                                            "'.$data['nombre_sucursal'].'");');
                    }

                    $this->insertoProvinciasFaltantes();
                }
                return back()->with('success', 'Se importo correctamente.');
            }
        }
        return back()->with('error', 'Verificar el Formato del archivo.');
    }

    public function muevoArchivosImages($imageName1, $path)
    {
        if (Input::file('import_file')) {
            //  $imageName1 = Input::get('cod_articulo') . "1" . Carbon::now()->toTimeString() . "." . Input::file('image_name_1')->getClientOriginalExtension();
            Input::file('import_file')->move(
                base_path() . $path, $imageName1);

        }
    }

    public function truncoTabla()
    {
        DB::select('truncate samira.pub_sucursales;');
        return;
    }

    private function insertoProvinciasFaltantes()
    {
        //Inserto el valor de la provincia 1 con nombre Otro
        DB::select('INSERT INTO samira.pub_sucursales (id_provincias, codigo_provincia, codigo_sucursal, nombre_sucursal)
                                    VALUES ("1", "NN","NN",
                                            "Otros");');
        //Inserto el valor de la provincia 26 con nombre Gran Buenos Aires
        DB::select('INSERT INTO samira.pub_sucursales (id_provincias, codigo_provincia, codigo_sucursal, nombre_sucursal)
                                    VALUES ("26", "NN","NN",
                                            "Gran Buenos Aires");');
    }
}
