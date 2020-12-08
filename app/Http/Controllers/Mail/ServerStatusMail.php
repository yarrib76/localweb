<?php

namespace Donatella\Http\Controllers\Mail;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use mysqli;

class ServerStatusMail extends Controller
{
    public function serverStatusMail()
    {

        //Las IP son de los servidores secundarios
        switch (gethostname()){
            case 'vagrant':
                $mysqli = new mysqli("192.168.0.20", "yarrib76", "NetAcc10", "samira");
                break;
            case 'viamoreapps':
                $mysqli = new mysqli("192.168.0.110", "yarrib76", "NetAcc10", "samira");
                break;
            case 'donapro':
                $mysqli = new mysqli("192.168.0.150", "root", "NetAcc10", "samira");
                break;
            case 'donaconti':
                $mysqli = new mysqli("192.168.0.150", "yarrib76", "NetAcc10", "samira");
                break;
        }
        $result = $mysqli->query("SELECT
	        (SELECT SERVICE_STATE FROM performance_schema.replication_connection_status) as Slave_IO_Running ,
	        (SELECT SERVICE_STATE FROM performance_schema.replication_applier_status) as Slave_SQL_Running;");
        $ultimoBkp = DB::select('SELECT fechafile from samira.statusbackup');
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $total = (floor(abs(strtotime($fecha)-strtotime(($ultimoBkp[0]->fechafile))))/60/60/24);
        $result=$result->fetch_assoc();
        $statusSlave_IO_Running = $result['Slave_IO_Running'];
        $statusSlave_SQL_Running = $result['Slave_SQL_Running'];
        $data = array('Slave_IO_Running'=>$statusSlave_IO_Running,'Slave_SQL_Running'=>$statusSlave_SQL_Running,
            'diasBackup'=>(int)$total);
        switch (gethostname()){
            case 'vagrant':
                Mail::send('mail.statusMail',$data,function($message){
                    $message->to('ventas@viamore.com.ar', 'Prueba de Mail')->subject
                    ('Estado de Backup y Replicas');
                    $message->from('yarrib76@gmail.com','Yamil Arribas');
                });
                break;
            case 'viamoreapps':
                Mail::send('mail.statusMail',$data,function($message){
                    $message->to('ventas@viamore.com.ar', 'Prueba de Mail')->subject
                    ('Estado de Backup y Replicas');
                    $message->from('yarrib76@gmail.com','Yamil Arribas');
                });
                break;
            case 'donaconti':
                Mail::send('mail.statusMail',$data,function($message){
                    $message->to('samira.srl@hotmail.com', 'Prueba de Mail')->subject
                    ('Estado de Backup y Replicas');
                    $message->from('yarrib76@gmail.com','Yamil Arribas');
                });
                break;
            case 'dbweb01':
                Mail::send('mail.statusMail',$data,function($message){
                    $message->to('bijoudonatella@hotmail.com', 'Prueba de Mail')->subject
                    ('Estado de Backup y Replicas');
                    $message->from('yarrib76@gmail.com','Yamil Arribas');
                });
                break;
        }

        dd('Listo');
    }
}
