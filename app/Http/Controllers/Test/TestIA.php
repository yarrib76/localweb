<?php

namespace Donatella\Http\Controllers\Test;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class TestIA extends Controller
{
    public function index()
    {
        return view('test.consulta_ia');
    }

    public function consultaIA()
    {
        $api_key = config('services.openai.api_key');
        $consultaHumana = Input::get('consultaHumana');
        $tarjetaCliente = $this->prepararTarjetaCliente();
        $asistente = $this->creoAsistente();
        $prompt = $this->crearPrompt($tarjetaCliente,$consultaHumana);
        $respuesta_data =$this->consultaApi($api_key,$asistente,$prompt);
        if (isset($respuesta_data['choices'][0]['message']['content'])) {
            return Response::json($respuesta_data['choices'][0]['message']['content']);
        }else {
        $respuesta = 'No se pudo obtener una respuesta de la API. Respuesta completa: ' . json_encode($respuesta_data);
        }
    }

    public function prepararTarjetaCliente()
    {
        //Consulta SQL
        $datos_cliente = DB::select('select cli.nombre as nombre_cliente, apellido, direccion, mail, telefono, cuit, localidad, provincias.nombre, encuesta, updated_at, created_at
                                from samira.clientes as cli
                                inner join samira.provincias On provincias.id = cli.id_provincia
                                where id_clientes = 3664');
        $datos_articulo = DB::select('SELECT  factura.Articulo as Articulo, factura.Detalle as Descripcion, sum(factura.Cantidad) as TotalVendido, factura.fecha, a.cantidad as Stock
                                FROM samira.facturah as facth
                                INNER JOIN samira.factura as factura ON facth.NroFactura = factura.NroFactura
                                INNER JOIN samira.articulos as a On a.Articulo = factura.articulo
                                where facth.id_clientes = 3664
                                GROUP BY factura.Articulo ORDER BY Total DESC
                                limit 10;');
        // $datos_envio = DB::select('');

        $tarjeta = [
            'cliente' => $datos_cliente,
            'articulos' => $datos_articulo
        ];
        return json_encode($tarjeta);
    }

    public function crearPrompt($tarjetaCliente,$consultaHumana)
    {
        //Creo el prompt para la consulta a la API
        $prompt = "Responder la siguiente pregunta " . $consultaHumana . " utilizando la información provista en lenjugaje natural.\n"
                . "Informacion:\n"
                . " . $tarjetaCliente . \n"
                . "Siempre responder en castellano:\n";
        return $prompt;
    }

    public function creoAsistente()
    {
        //Creamos el asistente para enviar a la API, le digo a la IA que comportamiento debe tener
        $asistente = "Eres uns asistente especialista en ventas";
        return $asistente;
    }

    public function consultaApi($api_key,$question,$prompt)
    {
        // La URL de la API de OpenAI
        $url = 'https://api.openai.com/v1/chat/completions';

        // Los datos de la solicitud
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => utf8_encode($question)
                ],
                [
                    'role' => 'user',
                    'content' => utf8_encode($prompt)
                ]
            ],
            'max_tokens' => 240
        ];

        // Inicializa cURL
        $ch = curl_init($url);

        // Codifica los datos a JSON
        $json_data = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        // Imprime el JSON para depuración
        //echo $json_data;

        // Ejecuta la solicitud y obtiene la respuesta
        $response = curl_exec($ch);

        // Comprueba si hay errores en cURL
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return 'Error en cURL: ' . $error_msg;
        }

        // Obtiene el código de estado HTTP
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Cierra cURL
        curl_close($ch);

        // Verifica el código de estado HTTP
        if ($http_status != 200) {
            return 'Error: Código de estado HTTP ' . $http_status . '. Respuesta completa: ' . $response;
        }

        // Decodifica la respuesta JSON
        $response_data = json_decode($response, true);
        return $response_data;
    }

}
