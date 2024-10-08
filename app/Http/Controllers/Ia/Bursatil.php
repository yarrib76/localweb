<?php

namespace Donatella\Http\Controllers\Ia;

use Donatella\Http\Controllers\Api\GetDataBursatil;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;


class Bursatil extends Controller
{
    public function inicio($apikey,$empresa)
    {
        $jsonDatosBursatil = $this->obtengoDatosBursatiles($apikey,$empresa);
        // return $jsonDatosBursatil;
        dump($jsonDatosBursatil);
        $rol = "Eres un experto en inversiones, con un perfil que est� dispuesto a asumir cierto nivel de riesgo en funci�n de una mejor rentabilidad.\n";
        $operadorBursatil = new ChatGPT();
        $prompt = $this->getPrompt($jsonDatosBursatil);
        $respuesta = $operadorBursatil->chatIA($rol,$prompt);
        return $respuesta;
    }

    public function obtengoDatosBursatiles($apikey,$empresa)
    {
        $datosBursatil = new GetDataBursatil();
        $jsonDatosBursatil = $datosBursatil->obtengoDatos($apikey,$empresa);
        // dump($jsonDatosBursatil);
        return $jsonDatosBursatil;
    }

    public function getPrompt($datosBursatiles)
    {
        $prompt = "Necesito que con los datos que te voy a proporcionar en formato json, hagas un analisis y me devuelvas lo siguiente:\n"
                . "Necesito que analises cada una de las empresas identificadas en el Json como Symbol y me proveeas las recomendaciones que enumerar�:\n"
                . "1. Que probabilidades tengo de ganar dinero, debe ser de la siguiente forma: Probabilidades Baja, Medio o Alta \n"
                . "2. Cuantos d�as me recomiendas mantener la compra de la acci�n. \n"
                . "3. Que porcentaje de posibilidad de ganar dinero tiene la accion, los rangos van de 1% a 100%"
                . "4. Para responer los puntos 1,2 y 3 debes utilizar en el analisis t�cnico retroceso de Fibonacci"
                . "Datos con informaci�n de la acci�n en fotmato Json\n"
                . ". $datosBursatiles .\n"
                . "Solo necesito que la respuesta sea en formato json con los siguiente item Symbol, Probabilidad de Ganar , Dias, Detalle, precioAccion, porcentaje. En detalle, hacer un peque�o resumen del analisis, especificando todos los indicadores t�cnicos y noticias que utilizaste para el resumen. \n"
                . "El formato JSON de la respuesta debe ser el siguiente:\n\n"
                . "```json\n"
                . "[\n"
                . "    {\n"
                . "        \"Symbol\": \"Ejemplo\",\n"
                . "        \"Probabilidad de Ganar\": \"Media\",\n"
                . "        \"Dias\": 14,\n"
                . "        \"Detalle\": \"La acci�n ha mostrado volatilidad con un precio reciente de 26.85 USD.\",\n"
                . "        \"precioAccion\": 26.85\n"
                . "        \"porcentaje\": 85%\n"
                . "    },\n"
                . "    {\n"
                . "        \"Symbol\": \"OtroEjemplo\",\n"
                . "        \"Probabilidad de Ganar\": \"Alta\",\n"
                . "        \"Dias\": 7,\n"
                . "        \"Detalle\": \"La acci�n tiene un precio estable con buen volumen de transacciones.\",\n"
                . "        \"precioAccion\": 15.30\n"
                . "        \"porcentaje\": 98%\n"
                . "    }\n"
                . "]\n"
                . "```";
        return $prompt;
    }

}
