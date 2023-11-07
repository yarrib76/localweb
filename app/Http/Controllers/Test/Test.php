<?php

namespace Donatella\Http\Controllers\Test;

use Carbon\Carbon;
use DateTime;
use Donatella\Ayuda\GetPuntos;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use mysqli;
//
class Test extends Controller
{
    public function Test()
    {
        //Priemer Commit miCorreo_V3
        
        $path = 'public/export/facturas/';
        //Elimino todos los archivos del directorio
        $this->eliminarFacturas($path);
        $nroFactura = Input::get('nroFactura');
        $nombreArchivo = Input::get('nombreCliente');
        $data = $this->consultaBase($nroFactura);
        if ($data){
            $this->storeExcel('xls',$data, $nombreArchivo,$path);
            $archivo = $this->bajoArchivo($nombreArchivo,$path);
        }
        return $archivo;
    }
    public function storeExcel($type, $data, $nombreArchivo,$path)
    {
        return Excel::create($nombreArchivo, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($type, storage_path($path));
    }
    private function consultaBase($nroFactura)
    {

        $data = DB::select('SELECT Articulo, Detalle, Cantidad, PrecioUnitario, PrecioVenta FROM samira.factura
                              where NroFactura = "'.$nroFactura.'" ');
        $data = json_decode(json_encode($data), true);
        return $data;
    }

    private static function bajoArchivo($nombreArchivo,$path)
    {
        $filePath = storage_path($path . $nombreArchivo . '.xls');
        $archivo = response()->download($filePath, $nombreArchivo . '.xls');
        return $archivo;
    }

    public function eliminarFacturas($path)
    {
        $directorio = storage_path($path);

        // Verificar si el directorio existe antes de intentar eliminar los archivos.
        if (File::isDirectory($directorio)) {
            // Eliminar todos los archivos del directorio.
            File::cleanDirectory($directorio);
            return "Se han eliminado todos los archivos de la carpeta 'facturas'.";
        } else {
            return "La carpeta 'facturas' no existe o no es accesible.";
        }
    }

}
