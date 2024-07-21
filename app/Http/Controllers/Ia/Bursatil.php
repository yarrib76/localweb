<?php

namespace Donatella\Http\Controllers\Ia;

use Donatella\Http\Controllers\Api\GetDataBursatil;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;

class Bursatil extends Controller
{
    public function inicio()
    {
        $jsonDatosBursatil = $this->obtengoDatosBursatiles();
        $rol = "Eres un experto en inversiones, con un perfil que está dispuesto a asumir cierto nivel de riesgo en función de una mejor rentabilidad.\n";
        $operadorBursatil = new ChatGPT();
        $prompt = $this->getPrompt($jsonDatosBursatil);
        $respuesta = $operadorBursatil->chatIA($rol,$prompt);
        return $respuesta;
    }

    public function obtengoDatosBursatiles()
    {
        $datosBursatil = new GetDataBursatil();
        $jsonDatosBursatil = $datosBursatil->obtengoDatos();
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
                . "Solo necesito que la respuesta sea Symbol, Probabilidad de Ganar y Días, no hace falta la explicación del analisis\n";
            return $prompt;
    }

}
