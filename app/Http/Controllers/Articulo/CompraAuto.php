<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CompraAuto extends Controller
{
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
            ('Envio Automatico, articulos alertados para compra de mercadería');
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
        $data = Articulos::where('Proveedor','Pacha')->get()->toArray();
        return $data;
    }
}
