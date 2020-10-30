<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CompraAuto extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index ()
    {
        return view('articulos.compraauto');
    }
    public function inicio()
    {
        $data = $this->consultaBase();
        $this->storeExcel('xls',$data);
        $this->envioMail();
        return 'Finalizado';
    }

    private function envioMail()
        {
        $data = array('Prueba'=>'1','Prueba'=>'2');
        Mail::send('mail.envioMail',$data,function($message){
            $message->to('yarrib76@gmail.com')->subject
            ('Envio Automatico, articulos alertados para compra de mercader�a');
            $message->from('yarrib76@gmail.com','Yamil Arribas');
            $message->attach( storage_path('public/export/CompraAuto.xls'), array ($options = []));
        });
    }


    public function storeExcel($type,$data)
    {
        $path = 'public/export';
        return Excel::create('CompraAuto', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->store($type, storage_path($path));
    }

    private function consultaBase()
    {
        $data = DB::select('SELECT Arti.Articulo, Arti.Detalle, Arti.Cantidad, compAuto.cant_alerta as Umbral, Arti.Proveedor FROM samira.compraautomatica as compAuto
                            INNER JOIN samira.articulos as Arti ON Arti.Articulo = CompAuto.Articulo
                            having compAuto.cant_alerta >= Arti.Cantidad');
        $data = json_decode(json_encode($data), true);
        return $data;
    }
}
