<?php

namespace Donatella\Http\Controllers\Articulo;

use Carbon\Carbon;
use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcel extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function importExport()
    {
        return view('articulos/importexport');
    }

    public function importExcel(Request $request)
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

            if (!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    $insert[] = ['articulo' => strval($value['articulo']), 'PrecioConvertido' => $value['precioconvertido'],
                    'PrecioManual' => $value['preciomanual'], 'PrecioOrigen' => $value['precioorigen']];
                }
                if (!empty($insert)) {
                    foreach ($insert as $update) {
                        /*Poner if y validar si existe el articulo, de no existir no realizar actualizaciones*/
                        $articuloActual = Articulos::where('articulo', $update['articulo'])->get();
                        if (!$articuloActual->isEmpty()) {
                            if ($update['PrecioManual'] == null) {
                                DB::select('UPDATE samira.articulos SET PrecioConvertido = "' . $update['PrecioConvertido'] . '", PrecioOrigen = "' . $update['PrecioOrigen'] . '"
                                WHERE Articulo = "' . $update['articulo'] . '";');
                                DB::select('INSERT INTO samira.importexcelcontrol (Articulo, PrecioOrigenViejo, PrecioOrigenNuevo, PrecioConvertidoViejo, PrecioConvertidoNuevo, Fecha, Status)
                                    VALUES ("' . $update['articulo'] . '","' . $articuloActual[0]['PrecioOrigen'] . '","' . $update['PrecioOrigen'] . '", "' . $articuloActual[0]['PrecioConvertido'] . '", "' . $update['PrecioConvertido'] . '", "' . $fecha . '","OK");');
                            } else {
                                DB::select('UPDATE samira.articulos SET PrecioManual = "' . $update['PrecioManual'] . '", PrecioOrigen = "' . $update['PrecioOrigen'] . '"
                                WHERE Articulo = "' . $update['articulo'] . '";');
                                DB::select('INSERT INTO samira.importexcelcontrol (Articulo, PrecioOrigenViejo, PrecioOrigenNuevo, PrecioManualViejo, PrecioManualNuevo, Fecha, Status)
                                    VALUES ("' . $update['articulo'] . '","' . $articuloActual[0]['PrecioOrigen'] . '","' . $update['PrecioOrigen'] . '", "' . $articuloActual[0]['PrecioManual'] . '", "' . $update['PrecioManual'] . '", "' . $fecha . '","OK");');

                            }
                        }else {
                            DB::select('INSERT INTO samira.importexcelcontrol (Articulo, Fecha , Status)
                                    VALUES ("' . $update['articulo'] . '", "' . $fecha . '","No Existe");');
                        }
                    }
                    //   Item::insert($insert);
                    return back()->with('success', 'Se importo correctamente.');
                }

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
}
