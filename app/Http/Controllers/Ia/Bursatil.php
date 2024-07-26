<?php

namespace Donatella\Http\Controllers\Ia;

use Donatella\Http\Controllers\Api\GetDataBursatil;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Bursatil extends Controller
{
    public function inicio($apikey,$empresa)
    {
        $jsonDatosBursatil = $this->obtengoDatosBursatiles($apikey,$empresa);
        $rol = "Eres un experto en inversiones, con un perfil que está dispuesto a asumir cierto nivel de riesgo en función de una mejor rentabilidad.\n";
        $operadorBursatil = new ChatGPT();
        $prompt = $this->getPrompt($jsonDatosBursatil);
        $respuesta = $operadorBursatil->chatIA($rol,$prompt);
        return $respuesta;
    }

    public function obtengoDatosBursatiles($apikey,$empresa)
    {
        $datosBursatil = new GetDataBursatil();
        $jsonDatosBursatil = $datosBursatil->obtengoDatos($apikey,$empresa);
        return $jsonDatosBursatil;
    }

    public function getPrompt($datosBursatiles)
    {
            $prompt = "Necesito que con los datos que te voy a proporcionar en formato json, hagas un analisis y me devuelvas lo siguiente:\n"
                . "Necesito que analises cada una de las empresas identificadas en el Json como Symbol y me proveeas las recomendaciones que enumeraré\n"
                . "1. Que probabilidades tengo de ganar dinero, debe ser de la siguiente forma: Probabilidades Baja, Medio o Alta \n"
                . "2. cuantos días me recomiendas mantener la compra de la acción. \n"
                . "Datos con información de la acción en fotmato Json\n"
                . ". $datosBursatiles .\n"
                . "Solo necesito que la respuesta sea en formato json con los siguiente item Symbol, Probabilidad de Ganar , Dias, Detalle, precioAccion. En detalle hacer un pequeño resumen del analisis. \n"
                . "El formato JSON de la respuesta debe ser el siguiente:\n\n"
                . "```json\n"
                . "[\n"
                . "    {\n"
                . "        \"Symbol\": \"Ejemplo\",\n"
                . "        \"Probabilidad de Ganar\": \"Media\",\n"
                . "        \"Dias\": 14,\n"
                . "        \"Detalle\": \"La acción ha mostrado volatilidad con un precio reciente de 26.85 USD.\",\n"
                . "        \"precioAccion\": 26.85\n"
                . "    },\n"
                . "    {\n"
                . "        \"Symbol\": \"OtroEjemplo\",\n"
                . "        \"Probabilidad de Ganar\": \"Alta\",\n"
                . "        \"Dias\": 7,\n"
                . "        \"Detalle\": \"La acción tiene un precio estable con buen volumen de transacciones.\",\n"
                . "        \"precioAccion\": 15.30\n"
                . "    }\n"
                . "]\n"
                . "```";
        return $prompt;
    }

}
