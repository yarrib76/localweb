<?php
/**
 * Created by PhpStorm.
 * User: viamore
 * Date: 06/22/2021
 * Time: 07:16 PM
 */

namespace Donatella\Http\Controllers\Mail;


use mysqli;

class ReporteSincro
{
    public function crearReporte()
    {
        $mysqliProd = new mysqli("192.168.0.104", "root", "NetAcc10", "samira");
        $mysqliConti = new mysqli("192.168.0.110", "yarrib76", "NetAcc10", "samira");
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
        return $resFinal;
    }
}