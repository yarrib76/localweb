<?php

namespace Donatella\Http\Controllers\Api;

use DateTime;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class GetDataBursatil extends Controller
{
    public function obtengoDatos($apikey,$empresa)
    {
        //$apikey = Input::get('apikey');
        // Definición de Parámetros
        // $apikey = 'H75CXB3AOHKM8Z';
        //$symbols = Input::get('empresa'); // Lista de símbolos que deseas analizar
        $symbols = $empresa;
        $range = '2month'; // Rango de fechas para el análisis
        $interval = 'DAILY'; // Intervalo de tiempo entre dos puntos de datos consecutivos
        $window_size = 20; // Tamaño de la ventana móvil
        $calculation = 'STDDEV(annualized=True)'; // Métrica analítica que deseas calcular

        // Noticias
        $months = 3;
        $timeRange = $this->getTimeRange($months);

        $time_from = $timeRange['time_from'];
        $time_to = $timeRange['time_to'];

        $ticker = $symbols; // Símbolo de la empresa que deseas analizar
        $topics = 'technology,financial_markets'; // Temas que deseas incluir
        $sort = 'LATEST'; // Ordenar por los artículos más recientes
        $limit = 50; // Número máximo de resultados

        $data_1 = "https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=$symbols&RANGE=$range&INTERVAL=$interval&OHLC=close&WINDOW_SIZE=$window_size&CALCULATIONS=$calculation&apikey=$apikey";
        $data_2 = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbols&apikey=$apikey";
        $data_3 = "https://www.alphavantage.co/query?function=NEWS_SENTIMENT&tickers=$ticker&time_from=$time_from&time_to=$time_to&sort=$sort&limit=$limit&apikey=$apikey";

        $urls = [$data_1, $data_2, $data_3];

        $curl_handles = [];
        $multi_handle = curl_multi_init();

        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
            curl_multi_add_handle($multi_handle, $ch);
            $curl_handles[] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($multi_handle, $running);
            curl_multi_select($multi_handle);
        } while ($running > 0);

        $responses = [];
        foreach ($curl_handles as $ch) {
            $response = curl_multi_getcontent($ch);
            $responses[] = json_decode($response, true); // Decodificar cada respuesta
            curl_multi_remove_handle($multi_handle, $ch);
            curl_close($ch);
        }

        curl_multi_close($multi_handle);

        // Combinar todas las respuestas en un solo array
        $combined_data = [
            'data_1' => isset($responses[0]) ? $responses[0] : [],
            'data_2' => isset($responses[1]) ? $responses[1] : [],
            'data_3' => isset($responses[2]) ? $responses[2] : []
        ];

        // Codificar el array combinado en JSON
        $json_output = json_encode($combined_data, JSON_PRETTY_PRINT);
        return $json_output;
    }


    function getTimeRange($months)
    {
        // Fecha actual
        $currentDate = new DateTime();

        // Clonar la fecha actual para manipular $time_from
        $timeFromDate = clone $currentDate;

        // Restar el número de meses
        $timeFromDate->modify("-$months months");

        // Formatear las fechas en el formato AAAAMMDDTHHMM
        $time_from = $timeFromDate->format('Ymd\THi');
        $time_to = $currentDate->format('Ymd\THi');

        // Devolver un array con los valores
        return [
            'time_from' => $time_from,
            'time_to' => $time_to
        ];
    }

    //Esta versión da TimeOut
    public function versionVieja()
    {
        //Deficinición de Parámetros
        $apikey = '1VZ9145OCAOCJ6MW';
        $symbols = 'MDBH'; // Lista de símbolos que deseas analizar
        $range = '2month'; // Rango de fechas para el análisis
        $interval = 'DAILY'; // Intervalo de tiempo entre dos puntos de datos consecutivos
        $window_size = 20; // Tamaño de la ventana móvil. Valor 20 o 30 para 2 meses, 100 para rangos de un año "$range" = 1year
        $calculation = 'STDDEV(annualized=True)'; // Métrica analítica que deseas calcular

        //Noticias
        //Obtengo Fecha de noticias X meses para atras.
        $months = 3;
        $timeRange = $this->getTimeRange($months);

        $time_from = $timeRange['time_from'];
        $time_to = $timeRange['time_to'];


        $ticker = 'MDBH'; // Símbolo de la empresa que deseas analizar
        $topics = 'technology,financial_markets'; // Temas que deseas incluir
        $time_from = $time_from; // Fecha de inicio para filtrar las noticias
        $time_to = $time_to; // Fecha de fin para filtrar las noticias (opcional)
        $sort = 'LATEST'; // Ordenar por los artículos más recientes
        $limit = 50; // Número máximo de resultados

        // $data_1 = ('https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=IBM&RANGE=2month&INTERVAL=DAILY&OHLC=close&WINDOW_SIZE=100&CALCULATIONS=STDDEV(annualized=True)&apikey=1VZ9145OCAOCJ6MW');
        $data_1 = "https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=$symbols&RANGE=$range&INTERVAL=$interval&OHLC=close&WINDOW_SIZE=$window_size&CALCULATIONS=$calculation&apikey=$apikey";
        $data_2 = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbols&apikey=$apikey";
        $data_3 = "https://www.alphavantage.co/query?function=NEWS_SENTIMENT&tickers=$ticker&time_from=$time_from&time_to=$time_to&sort=$sort&limit=$limit&apikey=$apikey";

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: PHP'
            ]
        ]);

        $contenido_data_1 = file_get_contents($data_1, false, $context);
        $contenido_data_2 = file_get_contents($data_2, false, $context);
        $contenido_data_3 = file_get_contents($data_3, false, $context);

        $contenidoCombinado = $this->concatenarDatos($contenido_data_1, $contenido_data_2, $contenido_data_3);

        return ($contenidoCombinado);

        //Cambiar apikey por H75CXB3AOHKM8ZMW
        $jsonData = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MEDS&apikey=MORNYP6KWJQAGVIP');
        //$jsonData = file_get_contents('https://www.alphavantage.co/query?function=INCOME_STATEMENT&symbol=MEDS&apikey=MORNYP6KWJQAGVIP');
        //$json = file_get_contents('https://www.alphavantage.co/query?function=OVERVIEW&symbol=IBM&apikey=demo');
        return $jsonData;
    }
    public function concatenarDatos($contenido_data_1, $contenido_data_2, $contenido_data_3)
    {
        // Decodificar JSON en arrays asociativos
        $array_1 = json_decode($contenido_data_1, true);
        $array_2 = json_decode($contenido_data_2, true);
        $array_3 = json_decode($contenido_data_3, true);
        // Combinar los arrays
        $array_combinado = array_merge($array_1, $array_2, $array_3);

        // Codificar el array combinado a JSON
        $combinado_json = json_encode($array_combinado);

        return $combinado_json;
    }

    //Esta versiópn resuelve el error con curl
    public function versionConCurl()
    {
        $url = 'https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=AAPL,IBM&RANGE=2month&INTERVAL=DAILY&OHLC=close&WINDOW_SIZE=20&CALCULATIONS=MEAN,STDDEV(annualized=True)&apikey=demo';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

// Ejecutar cURL
        $response = curl_exec($ch);

// Verificar errores
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            dd($response);
        }

// Cerrar cURL
        curl_close($ch);

        dd('hola');
        $url = 'https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=AAPL,IBM&RANGE=2month&INTERVAL=DAILY&OHLC=close&WINDOW_SIZE=20&CALCULATIONS=MEAN,STDDEV(annualized=True)&apikey=demo';

        $ch = curl_init();

// Configurar cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud y obtener la respuesta
        $response = curl_exec($ch);

// Verificar errores
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            // Decodificar la respuesta JSON
            dd($response);
        }

// Cerrar cURL
        curl_close($ch);
    }
}
