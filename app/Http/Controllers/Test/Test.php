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
use Illuminate\Support\Facades\Mail;
use mysqli;
//
class Test extends Controller
{
    //Commit DashBoard.. 
    public function Test()
    {
        $con = '';
        $res = array();
        try {
            $con = new \mysqli(env("DB_HOST", "localhost"), env("DB_USERNAME", "root"), env("DB_PASSWORD", 'NetAcc10'), env("DB_DATABASE", "samira"))
            or die('Could not connect to the database server' . mysqli_connect_error());
            if (empty($con))
                throw new \Exception("Connection is only allowed for authorized personnels.", 5001);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        $r = $con->query('CALL vendedoras_en_proceso()');
        while ($row = mysqli_fetch_array($r)) {
            $res[] = $row;
        }
        dd($res);
        $carbon = new \Carbon\Carbon();
        $datetime = $carbon->now();
        $datetime = (date(DATE_ISO8601, strtotime($datetime)));
        dd ($datetime);
        $mysqliProd = new mysqli("192.168.0.104", "root", "NetAcc10", "samira");
        $mysqliConti = new mysqli("192.168.0.110", "yarrib76", "NetAcc10", "samira");
        // $mysqliConti = new mysqli("192.168.0.109", "root", "NetAcc10", "samira");
        $resultProd = $mysqliProd->query("call checkRepli");
        $resultConti = $mysqliConti->query("call checkRepli");
        // $result=$result->fetch_assoc();
        $registrosProd[] = '';
        $registrosConti[] = '';
        $countConti = 0;
        $countProd = 0;
        while ($row = mysqli_fetch_array($resultConti)){
            $registrosConti[$countConti] = ['Campo' => $row[0], 'Registros' => $row[1]];
            $countConti++;
        }
        while ($row = mysqli_fetch_array($resultProd)){
            $registrosProd[$countProd] = ['Campo' => $row[0], 'Registros' => $row[1]];
            $countProd++;
        }

        $resFinal[] = '';
        if (count($registrosProd) == count($registrosConti)){
            for ($i =  0; $i <= count($registrosProd)-1; $i++){
                if ($registrosProd[$i]['Registros'] == $registrosConti[$i]['Registros']){
                    $resFinal[$i] = ['Campo' => $registrosProd[$i]['Campo'],'RegistroProd' => $registrosProd[$i]['Registros'],
                        'RegistroConti' =>  $registrosConti[$i]['Registros'],'Estado' => 'OK'];
                } else {
                    $resFinal[$i] = ['Campo' => $registrosProd[$i]['Campo'], 'RegistroProd' => $registrosProd[$i]['Registros'],
                        'RegistroConti' => $registrosConti[$i]['Registros'], 'Estado' => 'Error Sincro'];
                    }
            }
        }else $resFinal[0] = ['Estado' => 'Error Sincro'];

        $statusSlave_IO_Running = 'pepepe';
        $statusSlave_SQL_Running = 'dssadsa';
        $total = '12';
        $data = array('Slave_IO_Running'=>$statusSlave_IO_Running,'Slave_SQL_Running'=>$statusSlave_SQL_Running,
            'diasBackup'=>(int)$total,'resFinal'=>$resFinal);
        // dd($data);
        Mail::send('mail.statusMail',$data,function($message){
            $message->to('ventas@viamore.com.ar', 'Prueba de Mail')->subject
            ('Estado de Backup y Replicas');
            $message->from('yarrib76@gmail.com','Yamil Arribas');
        });
        dd($data);
    }
}
