<?php

namespace Donatella\Http\Controllers\Ia;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ChatGPT
{
    public function chatIA($question,$prompt,$modelo)
    {
        $respuesta_data = $this->consultaApi($question,$prompt,$modelo);
        if (isset($respuesta_data['choices'][0]['message']['content'])) {
            $respuesta = $respuesta_data['choices'][0]['message']['content'];
            return $respuesta;
        } else {
            return $respuesta = 'Por favor, volver a realizar la consulta, verifique la claridad de la misma';
        }
        return $respuesta;
    }

    public function consultaApi($question,$prompt,$modelo)
    {
        // Tu clave API de OpenAI
        $api_key = config('services.openai.api_key');

        // La URL de la API de OpenAI
        $url = 'https://api.openai.com/v1/chat/completions';

        /*Modelo 0 = gpt-4o-mini
          Modelo 1 = o1-mini Con razonammiento
        */
        // Los datos de la solicitud
        if ($modelo == 0){
            $data = [
                'model' => 'gpt-4o-mini',
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
                'max_tokens' => 15000,
                'temperature'=> 0.2,
            ];
        }
        if ($modelo == 1){
            $data = [
                'model' => 'o1-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => utf8_encode($prompt)
                    ]
                ],
            ];
        }


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
