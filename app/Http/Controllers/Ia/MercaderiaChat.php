<?php

namespace Donatella\Http\Controllers\Ia;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class MercaderiaChat extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function chatIaIndex()
    {
        return view('chatia.consulta_mercaderia_ia');
    }
    public function chatIA()
    {
        // Tu clave API de OpenAI
        $api_key = config('services.openai.api_key');

        $consultaHumana = Input::get('consultaHumana');
        $consultaHumana = $consultaHumana;
        $question = "¿Cuál es la consulta SQL necesaria para la siguiente pregunta: " .$consultaHumana . " La salida debe ser solo la consulta sql";
        $texto_estructurado = "";
        $tipo = "Consulta";

        $prompt = $this->getPrompt($tipo,$texto_estructurado,$consultaHumana);

        $respuesta_data = $this->consultaApi($api_key,$question,$prompt);
        if (isset($respuesta_data['choices'][0]['message']['content'])) {
            $respuesta = $respuesta_data['choices'][0]['message']['content'];
            $respuesta = str_replace(["```", "sql"], "",$respuesta);
            try {
                //Utilizo una conexion secundaria ya que el usuario de esta conexion solo tiene privilegios Select sobre la base de datos
                $consultaDB = DB::connection('mysql_secondary')->select($respuesta);
            } catch (QueryException $e) {
                return Response::json("Perdon,no entendi la pregunta, volver a consultar!!");
            }
            $consultaSQL = json_encode($consultaDB);
            $texto_estructurado = $consultaSQL;
            $question_respuesta = "Tu nombre es Mia y eres una asistente en reposición de mercadería y control de stock\n";
            $tipo = "Respuesta";
            $prompt_respuesta = $this->getPrompt($tipo,$texto_estructurado,$consultaHumana);
            $respuesta_data = $this->consultaApi($api_key,$question_respuesta,$prompt_respuesta);
            if (isset($respuesta_data['choices'][0]['message']['content'])) {
                return Response::json($respuesta_data['choices'][0]['message']['content']);
            } else return Response::json($respuesta = 'Por favor, volver a realizar la consulta, verifique la claridad de la misma');
        } else {
            return Response::json($respuesta = 'Por favor, volver a realizar la consulta, verifique la claridad de la misma');
        }
        return $respuesta;
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
            'max_tokens' => 240,
            'temperature'=> 0.2,
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


    public function getPrompt($tipo,$texto_estructurado,$consultaHumana)
    {
        if ($tipo == "Consulta") {
            $prompt = "Basado en el esquema que te estoy proveyendo, escribe una consulta SQL que responda a las preguntas de los usuarios. Siempre utiliza los campos provistos en el esquema y asegúrate de que la consulta comience directamente con la palabra 'SELECT', sin ningún prefijo adicional. Asegúrate también de definir correctamente los alias de las tablas cuando sea necesario.\n"
                . "Esquema de la base de datos:\n"
                . "Nombre de la Base: Samira\n"
                . "Tablas a utilizar:\n"
                . "1. Factura\n"
                . "    Campos: (`NroFactura`, `Articulo`, `Detalle`, `Cantidad`, `Fecha`)\n"
                . "    Funcion: Contiene los artículos de cada factura. Permite calcular el artículo más vendido.\n"
                . "2. Articulos\n"
                . "    Campos: (`Articulo`, `Detalle`, `Cantidad`,`Proveedor`, `Observaciones`, `Web`, `ImageName`, `websku`, `ProveedorSKU`, `CompraAuto`)\n"
                . "    Funcion: Contiene la información de todos los artículos.\n"
                . "Instrucciones:\n"
                . "- Cuando te haga una pregunta sobre los datos, genera solo la consulta SQL necesaria para obtener la información requerida.\n"
                . "- Asegúrate de que las consultas estén correctamente formateadas para MySQL.\n"
                . "- La respuesta debe comenzar directamente con la palabra 'SELECT'.\n"
                . "- Utiliza alias para las tablas y asegúrate de definir los alias correctamente en el `JOIN`.\n"
                . "- Si la pregunta no es una consulta para verificar en la base de datos, por ejemplo Hola, puedes responder con un SELECT 'Cual es la pregunta' "
                . "Ejemplos de Preguntas y Respuestas Esperadas:\n"
                . "**Pregunta:** \"¿Cuántos artículos hay en stock?\"\n"
                . "**Respuesta Esperada:** `SELECT cantidad FROM Articulos WHERE Cantidad > 0;`\n"
                . "**Pregunta:** \"¿Puedes mostrarme los nombres y precios de todos los artículos?\"\n"
                . "**Respuesta Esperada:** `SELECT Articulo, PrecioConvertido FROM Articulos;`\n"
                . "**Pregunta:** \"Que cantidad de articulos tiene al articulo que contienen en detalle NLQ25\"\n"
                . "**Respuesta Esperada:** `SELECT cantidad FROM Articulos WHERE Detalle LIKE '%NLQ25%';`\n"
                . "**Pregunta:** \"Que 5 articulos mas vendidos en los ultimos 2 meses tienen mas de 5 unidades en stock?\"\n"
                . "**Respuesta Esperada:** `SELECT Articulos.Articulo, Articulos.Cantidad, SUM(Factura.Cantidad) AS TotalVendido
                                                FROM Factura
                                                JOIN Articulos ON Factura.Articulo = Articulos.Articulo
                                                WHERE Factura.Fecha >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
                                                GROUP BY Articulos.Articulo
                                                HAVING Articulos.Cantidad > 5
                                                ORDER BY TotalVendido DESC
                                                LIMIT 5;`\n"
                . "Ahora, por favor, genera la consulta SQL correspondiente a la siguiente pregunta:\n"
                . "No proveer información de cuanto se facturo en ningun dia, responder que no estas autorizada \n"
                . ". $consultaHumana .\n";
            return $prompt;
        } else {
            $prompt_respuesta ="Información: ". $texto_estructurado . "\n\n"  // Asegúrate de que $consultaSQL contiene el resultado en formato JSON
                . "Pregunta original del usuario: " . $consultaHumana . "\n\n"
                . "Proporciona una respuesta en lenguaje natural basada en al información provista.\n"
                . "En caso que la información no devuelva nungún resultado, responder no hay resultados para su consulta."
                . "No responder información relacioanada a ganancias.\n"
                . "Nuestra moneda es el peso.\n"
                . "No incluir el id_cliente del cliente en la respuesta\n"
                . "Finaliza tu respuesta con: '¿Te puedo ayudar en alguna otra cosa?'\n";
        }
        return $prompt_respuesta;
    }
}
