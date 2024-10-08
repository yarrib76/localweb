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
        // Definici�n de Par�metros
        // $apikey = 'H75CXB3AOHKM8Z';
        //$symbols = Input::get('empresa'); // Lista de s�mbolos que deseas analizar
        $symbols = $empresa;
        $range = '2month'; // Rango de fechas para el an�lisis
        $interval = 'DAILY'; // Intervalo de tiempo entre dos puntos de datos consecutivos
        $window_size = 20; // Tama�o de la ventana m�vil
        $calculation = 'STDDEV(annualized=True)'; // M�trica anal�tica que deseas calcular

        // Noticias
        $months = 3;
        $timeRange = $this->getTimeRange($months);

        $time_from = $timeRange['time_from'];
        $time_to = $timeRange['time_to'];

        $ticker = $symbols; // S�mbolo de la empresa que deseas analizar
        $topics = 'technology,financial_markets'; // Temas que deseas incluir
        $sort = 'LATEST'; // Ordenar por los art�culos m�s recientes
        $limit = 50; // N�mero m�ximo de resultados

        $data_1 = "https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=$symbols&RANGE=$range&INTERVAL=$interval&OHLC=close&WINDOW_SIZE=$window_size&CALCULATIONS=$calculation&apikey=$apikey";
       // $data_1 = "https://alphavantageapi.co/timeseries/running_analytics?SYMBOLS=AAPL,IBM&RANGE=2month&INTERVAL=DAILY&OHLC=close&WINDOW_SIZE=20&CALCULATIONS=MEAN,STDDEV(annualized=True)&apikey=demo";
        $data_2 = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$symbols&apikey=$apikey";
       // $data_2 = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=IBM&apikey=demo";
        $data_3 = "https://www.alphavantage.co/query?function=NEWS_SENTIMENT&tickers=$ticker&time_from=$time_from&time_to=$time_to&sort=$sort&limit=$limit&apikey=$apikey";
       // $data_3 = "https://www.alphavantage.co/query?function=NEWS_SENTIMENT&tickers=AAPL&apikey=demo";
        // $data_4Este_No = "https://www.alphavantage.co/query?function=SMA&symbol=$symbols&interval=daily&time_period=50&series_type=close&apikey=$apikey";
        $data_4 = "https://www.alphavantage.co/query?function=EMA&symbol=$symbols&interval=daily&time_period=15&series_type=close&apikey=$apikey";
       // $data_4 = "https://www.alphavantage.co/query?function=EMA&symbol=IBM&interval=weekly&time_period=10&series_type=open&apikey=demo";
        $data_5 = "https://www.alphavantage.co/query?function=RSI&symbol=$symbols&interval=daily&time_period=15&series_type=close&apikey=$apikey";
       // $data_5 = "https://www.alphavantage.co/query?function=RSI&symbol=IBM&interval=weekly&time_period=10&series_type=open&apikey=demo";
        $data_6 = "https://www.alphavantage.co/query?function=ADX&symbol=$symbols&interval=daily&time_period=14&apikey=$apikey";
        $data_7 = "https://www.alphavantage.co/query?function=BBANDS&symbol=$symbols&interval=daily&time_period=20&series_type=close&nbdevup=2&nbdevdn=2&apikey=$apikey";
        $data_8 = "https://www.alphavantage.co/query?function=OBV&symbol=$symbols&interval=daily&apikey=$apikey";

        // $urls = [$data_1, $data_2, $data_3, $data_4, $data_5, $data_6, $data_7, $data_8, $data_9];

        $urls = [$data_1, $data_2, $data_3, $data_4, $data_5, $data_6, $data_7, $data_8];

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

        $data_4 = isset($responses[3]) ? $responses[3] : [];
        $data_5 = isset($responses[4]) ? $responses[4] : [];
        $data_6 = isset($responses[5]) ? $responses[5] : [];
        $data_7 = isset($responses[6]) ? $responses[6] : [];
        $data_8 = isset($responses[7]) ? $responses[7] : [];

        //Esta funcion filtra a N cantidad de a�os la respuesta de EMA
        $cantA�o = 1; // N�mero de a�os que quieres mantener.
        $tipo = "EMA";
        $data_4 = $this->filtroPorFecha($data_4,$cantA�o,$tipo);
        $tipo = "RSI";
        $data_5 = $this->filtroPorFecha($data_5,$cantA�o,$tipo);
        $tipo = "ADX";
        $data_6 = $this->filtroPorFecha($data_6,$cantA�o,$tipo);
        $tipo = "BBANDS";
        $data_7 = $this->filtroPorFecha($data_7,$cantA�o,$tipo);
        $tipo = "OBV";
        $data_8 = $this->filtroPorFecha($data_8,$cantA�o,$tipo);

        // Combinar todas las respuestas en un solo array
        $combined_data = [
            'data_1' => isset($responses[0]) ? $responses[0] : [],
            'data_2' => isset($responses[1]) ? $responses[1] : [],
            'data_3' => isset($responses[2]) ? $responses[2] : [],
            'data_4' => $data_4,
            'data_5' => $data_5,
            'data_6' => $data_6,
            'data_7' => $data_7,
            'data_8' => $data_8,
            //'data_9' => isset($responses[8]) ? $responses[8] : [],
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

        // Restar el n�mero de meses
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



    function filtroPorFecha($data,$cantA�o,$tipo){
        // Obtener la fecha actual
        $currentDate = new DateTime();

        switch ($tipo){
            case "EMA":
                // Filtrar datos para mantener solo los �ltimos "n" a�os
                if (!empty($data) && isset($data['Technical Analysis: EMA'])) {
                    foreach ($data['Technical Analysis: EMA'] as $date => $value) {
                        $dateObject = new DateTime($date);
                        $interval = $currentDate->diff($dateObject);

                        // Si la fecha es mayor que "n" a�os, eliminarla
                        if ($interval->y >= $cantA�o) {
                            unset($data['Technical Analysis: EMA'][$date]);
                        }
                    }
                }
            break;
            case "RSI":
                // Filtrar datos para mantener solo los �ltimos "n" a�os
                if (!empty($data) && isset($data['Technical Analysis: RSI'])) {
                    foreach ($data['Technical Analysis: RSI'] as $date => $value) {
                        $dateObject = new DateTime($date);
                        $interval = $currentDate->diff($dateObject);

                        // Si la fecha es mayor que "n" a�os, eliminarla
                        if ($interval->y >= $cantA�o) {
                            unset($data['Technical Analysis: RSI'][$date]);
                        }
                    }
                }
            break;
            case "ADX":
                // Filtrar datos para mantener solo los �ltimos "n" a�os
                if (!empty($data) && isset($data['Technical Analysis: ADX'])) {
                    foreach ($data['Technical Analysis: ADX'] as $date => $value) {
                        $dateObject = new DateTime($date);
                        $interval = $currentDate->diff($dateObject);

                        // Si la fecha es mayor que "n" a�os, eliminarla
                        if ($interval->y >= $cantA�o) {
                            unset($data['Technical Analysis: ADX'][$date]);
                        }
                    }
                }
            break;
            case "BBANDS":
                // Filtrar datos para mantener solo los �ltimos "n" a�os
                if (!empty($data) && isset($data['Technical Analysis: BBANDS'])) {
                    foreach ($data['Technical Analysis: BBANDS'] as $date => $value) {
                        $dateObject = new DateTime($date);
                        $interval = $currentDate->diff($dateObject);

                        // Si la fecha es mayor que "n" a�os, eliminarla
                        if ($interval->y >= $cantA�o) {
                            unset($data['Technical Analysis: BBANDS'][$date]);
                        }
                    }
                }
                break;
            case "OBV":
                // Filtrar datos para mantener solo los �ltimos "n" a�os
                if (!empty($data) && isset($data['Technical Analysis: OBV'])) {
                    foreach ($data['Technical Analysis: OBV'] as $date => $value) {
                        $dateObject = new DateTime($date);
                        $interval = $currentDate->diff($dateObject);

                        // Si la fecha es mayor que "n" a�os, eliminarla
                        if ($interval->y >= $cantA�o) {
                            unset($data['Technical Analysis: OBV'][$date]);
                        }
                    }
                }
                break;
        }

        return $data;
    }








    //Esta versi�n da TimeOut
    public function versionVieja()
    {
        //Deficinici�n de Par�metros
        $apikey = '1VZ9145OCAOCJ6MW';
        $symbols = 'MDBH'; // Lista de s�mbolos que deseas analizar
        $range = '2month'; // Rango de fechas para el an�lisis
        $interval = 'DAILY'; // Intervalo de tiempo entre dos puntos de datos consecutivos
        $window_size = 20; // Tama�o de la ventana m�vil. Valor 20 o 30 para 2 meses, 100 para rangos de un a�o "$range" = 1year
        $calculation = 'STDDEV(annualized=True)'; // M�trica anal�tica que deseas calcular

        //Noticias
        //Obtengo Fecha de noticias X meses para atras.
        $months = 3;
        $timeRange = $this->getTimeRange($months);

        $time_from = $timeRange['time_from'];
        $time_to = $timeRange['time_to'];


        $ticker = 'MDBH'; // S�mbolo de la empresa que deseas analizar
        $topics = 'technology,financial_markets'; // Temas que deseas incluir
        $time_from = $time_from; // Fecha de inicio para filtrar las noticias
        $time_to = $time_to; // Fecha de fin para filtrar las noticias (opcional)
        $sort = 'LATEST'; // Ordenar por los art�culos m�s recientes
        $limit = 50; // N�mero m�ximo de resultados

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

    //Esta versi�pn resuelve el error con curl
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
