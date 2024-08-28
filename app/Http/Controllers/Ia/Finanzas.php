<?php

namespace Donatella\Http\Controllers\Ia;

use Carbon\Carbon;
use Donatella\Models\ChatIAPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class Finanzas extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function chatIaIndex()
    {
        return view('test.consulta_ia');

    }
    public function iniciarChat()
    {
        $path = '/public/importacion';
        $excelFileName = "finanzaIA.xlsx";
        $path = $path . "/" . $excelFileName;
        $datosFinancieros = $this->obtengoInfoExcel($path);
        // return $datosFinancieros;
        $consultaHumana = Input::get('consultaHumana');
        $id_user = Input::get('id_user');
        $tipo = "Consulta";
        // $datosFinancieros = "";
        $question_respuesta = "Eres una especialista en el area de finanzas  \n";
        $prompt_respuesta = $this->getPrompt($tipo,$datosFinancieros,$consultaHumana);
        $asistenteVentas = new ChatGPT();
        $respuesta = $asistenteVentas->chatIA($question_respuesta,$prompt_respuesta);
        //$id_user = DB::Select('select id from samira.users where name="Mia"');
        //$this->guardaChat($id_pedido,$id_user[0]->id,$respuesta);
        return Response::json($respuesta);
    }


    public function getPrompt($tipo,$texto_respuesta,$consultaHumana)
    {
        if ($tipo == "Consulta") {
            $prompt = "Eres un asistente inteligente especializado en el �rea de finanzas. "
                . "Recibir�s datos detallados de un sistema de finanzas, incluyendo informaci�n sobre todos los gastos de la empresa. "
                . "Tu tarea es analizar la informaci�n seg�n lo solicitado por nuestro asistente. "
                . "Debes responder en lenguaje natural. "
                . "Solicitud del asistente: " . $consultaHumana . "\n\n"
                . "Ejemplo de los datos provistos para que lo puedas interpretar mejor:\n\n"
                . "```json\n"
                . "[\n"
                . "    {\n"
                . "        \"Concepto\": \"Concepto del Gasto\",\n"
                . "        \"Importe\": \"Importe del Gasto\"\n"
                . "        \"Fecha\": \"Corresponde al Mes\"\n"
                . "    }\n"
                . "]\n"
                . "```"
                . "Datos detallados en formato JSON. " . $texto_respuesta . "\n\n";
          //  $prompt = "Responder la siguiente pregunta: " .$consultaHumana . "\n";
            return $prompt;
        } else {
            $prompt_respuesta = "Datos detallados de un sistema de facturaci�n. " . $texto_respuesta . "\n\n"
                . "Eres un asistente inteligente especializado en gesti�n de inventarios y compras. "
                . "Recibir�s datos detallados de un sistema de facturaci�n, incluyendo informaci�n sobre ventas, "
                . "niveles de stock, y detalles de productos. Tu tarea es analizar esta informaci�n y generar una lista "
                . "de art�culos que deber�an ser comprados. Esta lista debe incluir tanto los productos que necesitan "
                . "ser repuestos debido a bajos niveles de stock, como los art�culos que tienen similitudes con productos "
                . "de alta demanda y podr�an ser beneficiosos para mantener en inventario. Aseg�rate de considerar tanto "
                . "la reposici�n como las oportunidades de ventas basadas en patrones de compra y tendencias.\n"

                . "En caso que la informaci�n no devuelva ning�n resultado, responde 'No hay resultados para su consulta.'\n"

                . "La respuesta debe ser en formato JSON y contener las siguientes columnas:\n"
                . "Articulo: Debe contener el n�mero de art�culo, "
                . "Detalle: Debe contener el detalle del art�culo, "
                . "Cantidad: Debe contener la cantidad recomendable a comprar en funci�n de las ventas, "
                . "Probabilidad: Debe contener en porcentaje la probabilidad de ser vendido en funci�n del tipo de art�culo. "
                . "Este an�lisis debe contemplar la tendencia del tipo de art�culo especificado en la columna Detalle; "
                . "por ejemplo, si es una cadena de acero, se debe verificar si ese tipo de art�culo fue tendencia en alg�n "
                . "otro c�digo y sus ventas anteriores. "
                . "Motivo: Describe el motivo por el cual recomiendas comprar este art�culo y c�mo se calcul� la probabilidad.\n"
                . "Similitud: Debe ser 3 codigo de articulo similar seg�n las caracteristicas descriptas en el campo Detalle y su precio de Origen"

                . "La respuesta debe contener como m�mino 150 y un m�ximo de 200 articulos ordenados de Mayor a Menor seg�n su Probabilidad"

                . "\n"
                . "El formato JSON de la respuesta debe ser el siguiente:\n\n"
                . "```json\n"
                . "[\n"
                . "    {\n"
                . "        \"Articulo\": \"7798544000749\",\n"
                . "        \"Detalle\": \"Cadena de Acero 0.6 NC74 60cm\",\n"
                . "        \"Cantidad\": 14,\n"
                . "        \"Probabilidad\": \"20%\",\n"
                . "        \"Motivo\": \"El an�lisis determin� que las cadenas de acero tuvieron mayor tendencia a ser elegidas por los clientes.\"\n"
                . "        \"Similitud\": \"7798544008943,7798632008933,7798864001933\",\n"
                . "    },\n"
                . "    {\n"
                . "        \"Articulo\": \"7798430010227\",\n"
                . "        \"Detalle\": \"Anillo Acero cora NA1022\",\n"
                . "        \"Cantidad\": 40,\n"
                . "        \"Probabilidad\": \"80%\",\n"
                . "        \"Motivo\": \"El an�lisis determin� que los clientes prefirieron los anillos de acero sobre otros tipos de anillos, como el acero blanco o dorado.\"\n"
                . "        \"Similitud\": \"7798982000332,7798982000621,7798957000162\",\n"
                . "    }\n"
                . "]\n"
                . "```";
        }
        return $prompt_respuesta;
    }
    public function guardaChat($id_pedido, $id_user, $chat)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        ChatIAPedidos::create([
            'id_controlpedidos' => $id_pedido,
            'id_users' => $id_user,
            'chat' => $chat,
            'fecha' => $fecha
        ]);
    }
    public function carga_chatIA()
    {
        $id_pedido = Input::get('id_pedido');
        $chat = DB::select('SELECT u.name as nombre, c.chat, c.fecha FROM samira.chatpedidosia c
                                inner join samira.users u on u.id = c.id_users
                                where id_controlpedidos = "'.$id_pedido.'"
                                order by fecha asc;');
        return Response::json($chat);
    }

    public function importExcelIa(Request $request){
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        if ($request->hasFile('import_file')) {
            $path = '/public/importacion';
            $excelFileName = "finanzaIA.xlsx";
            $this->muevoArchivosImages($excelFileName, $path);
            return back()->with('success', 'Se importo correctamente.');
        }
    }
    public function muevoArchivosImages($excelFile, $path)
    {
        if (Input::file('import_file')) {
            //  $imageName1 = Input::get('cod_articulo') . "1" . Carbon::now()->toTimeString() . "." . Input::file('image_name_1')->getClientOriginalExtension();
            Input::file('import_file')->move(
                base_path() . $path, $excelFile);
        }
    }

    private function formateoExcel($data)
    {
        // Inicializar un array para almacenar los resultados
        $result = [];
        // Iterar sobre cada hoja en el archivo Excel (SheetCollection)
        foreach ($data as $sheet) {
            // Acceder al t�tulo de la hoja
            $title = $sheet->getTitle(); // M�todo para obtener el t�tulo

            // Inicializar un array para almacenar los items de la hoja actual
            $sheetItems = [];

            // Iterar sobre cada fila en la hoja (RowCollection)
            foreach ($sheet as $row) {
                // Puedes procesar cada fila aqu� y agregarla a $sheetItems
                $rowData = [];

                // Iterar sobre cada celda en la fila (CellCollection)
                foreach ($row as $key => $cell) {
                    // Guardar los datos de la celda en el array de fila
                    if ($cell <> null){
                        if (($key) === 'importe'){
                            $rowData[$key] = round((float) $cell,2);
                        }else $rowData[$key] = $cell;
                    }
                }
                // Agregar la fila procesada al array de items de la hoja
                if (!empty($rowData)){
                    $sheetItems[] = $rowData;
                }
            }

            // Agregar los items de la hoja al resultado usando el t�tulo como clave
            $result[$title] = $sheetItems;
        }

        // Convertir el resultado a JSON
        $json = json_encode($result, JSON_PRETTY_PRINT);
        return ($json);
    }

    private function obtengoInfoExcel($path)
    {
        $data = Excel::load($path, function ($reader) {
        })->get();
        $dataJson = $this->formateoExcel($data);
        return $dataJson;
    }
}
