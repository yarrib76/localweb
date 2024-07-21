<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class GetDataBursatil extends Controller
{
    public function obtengoDatos()
    {

        $data_1 = ('https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=AAPL,IBM&RANGE=2month&INTERVAL=DAILY&OHLC=close&WINDOW_SIZE=20&CALCULATIONS=MEAN,STDDEV(annualized=True)&apikey=demo');
        $data_2 = ('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=IBM&apikey=demo');

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: PHP'
            ]
        ]);

        $contenido_data_1 = file_get_contents($data_1, false, $context);
        $contenido_data_2 = file_get_contents($data_2, false, $context);

        $contenidoCombinado = $this->concatenarDatos($contenido_data_1,$contenido_data_2);

        return($contenidoCombinado);

        //Cambiar apikey por H75CXB3AOHKM8ZMW
        $jsonData = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MEDS&apikey=MORNYP6KWJQAGVIP');
        //$jsonData = file_get_contents('https://www.alphavantage.co/query?function=INCOME_STATEMENT&symbol=MEDS&apikey=MORNYP6KWJQAGVIP');
        //$json = file_get_contents('https://www.alphavantage.co/query?function=OVERVIEW&symbol=IBM&apikey=demo');
        return $jsonData;
    }

    public function concatenarDatos($contenido_data_1,$contenido_data_2)
    {
        // Decodificar JSON en arrays asociativos
        $array_1 = json_decode($contenido_data_1, true);
        $array_2 = json_decode($contenido_data_2, true);
        // Combinar los arrays
        $array_combinado = array_merge($array_1, $array_2);

        // Codificar el array combinado a JSON
        $combined_json = json_encode($array_combinado);

        return $combined_json;
    }
}
