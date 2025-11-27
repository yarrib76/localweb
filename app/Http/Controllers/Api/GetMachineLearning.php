<?php

namespace Donatella\Http\Controllers\Api;

use Illuminate\Http\Request;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;


class GetMachineLearning extends Controller
{
    public function consultaPredictor(Request $request)
    {
        $data = $request->all();

        $sku = $data['sku'];
        $periodos = $data['periodos'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://192.168.0.154:8000/prediccion/sku");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'sku'      => $sku,
            'periodos' => $periodos
        ]));

        $response = curl_exec($ch);

        if ($response === false) {
            return response()->json([
                'error' => 'Error llamando a FastAPI',
                'detail' => curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        return response()->json(json_decode($response, true));
    }
}
