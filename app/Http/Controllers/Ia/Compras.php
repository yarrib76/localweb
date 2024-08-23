<?php

namespace Donatella\Http\Controllers\Ia;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Compras extends Controller
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
        $consultaHumana = Input::get('consultaHumana');
        $id_user = Input::get('id_user');
        // $this->guardaChat($id_pedido,$id_user,$consultaHumana);
        //$consultaHumana = $consultaHumana;
        $question_SQL = "�Cu�l es la consulta SQL necesaria para la siguiente pregunta: " .$consultaHumana . " La salida debe ser solo la consulta sql";
        $tipo = "Consulta";
        $texto_respuesta = "";
        $prompt = $this->getPrompt($tipo,$texto_respuesta,$consultaHumana);
        $asistenteSQL = new ChatGPT();
        $respuesta = $asistenteSQL->chatIA($question_SQL,$prompt);
        $respuesta = str_replace(["```", "sql"], "",$respuesta);
        dump($respuesta);
        try {
            //Utilizo una conexion secundaria ya que el usuario de esta conexion solo tiene privilegios Select sobre la base de datos
            $consultaDB = DB::connection('mysql_secondary')->select($respuesta);
        } catch (QueryException $e) {
            return Response::json("Perdon,no entendi la pregunta o se genero un error en la consulta, consultar nuevamente!!");
        }
        $texto_respuesta = json_encode($consultaDB);
        $question_respuesta = "Eres una especialista en el area de compras  \n";
        $tipo = "Respuesta";
        $prompt_respuesta = $this->getPrompt($tipo,$texto_respuesta,$consultaHumana);
        $asistenteVentas = new ChatGPT();
        $respuesta = $asistenteVentas->chatIA($question_respuesta,$prompt_respuesta);
        //$id_user = DB::Select('select id from samira.users where name="Mia"');
        //$this->guardaChat($id_pedido,$id_user[0]->id,$respuesta);
        return Response::json($respuesta);
    }


    public function getPrompt($tipo,$texto_respuesta,$consultaHumana)
    {
        if ($tipo == "Consulta") {
            $prompt = "Basado en el esquema que te estoy proveyendo, escribe una consulta SQL que responda a las preguntas de los usuarios. Siempre utiliza los campos provistos en el esquema y aseg�rate de que la consulta comience directamente con la palabra 'SELECT', sin ning�n prefijo adicional. Aseg�rate tambi�n de definir correctamente los alias de las tablas cuando sea necesario.\n"
                . "Esquema de la base de datos:\n"
                . "Nombre de la Base: Samira\n"
                . "Tablas a utilizar:\n"
                . "1. Factura\n"
                . "    Campos: (`Articulo` varchar(255), `Detalle` varchar(255), `Cantidad` int(11), `Fecha` datetime)\n"
                . "    Funcion: Contiene los art�culos vendidos en cada factura. Sirve para analizar que art�culos fueron vendidos, la cantidad del campo cantidad no representa el stock del articulo, solo cuantos de vendieron\n"
                . "2. Articulos\n"
                . "    Campos: (`Articulo` varchar(255) PK, `Detalle` varchar(255), `Cantidad` int(11), PrecioOrigen double)\n"
                . "    Funcion: Contiene la informaci�n de todos los art�culos. Para el campo cantidad utilizar el alias stock\n"
                . "Instrucciones:\n"
                . "- Cuando te haga una pregunta sobre los datos, genera solo la consulta SQL necesaria para obtener la informaci�n requerida.\n"
                . "- Aseg�rate de que las consultas est�n correctamente formateadas para MySQL.\n"
                . "- La respuesta debe comenzar directamente con la palabra 'SELECT'.\n"
                . "- Utiliza alias para las tablas y aseg�rate de definir los alias correctamente en el `JOIN`.\n"
                . "- Es muy importante que no confundas entre cantidad disponible y la cantidad comprada de un articulo. La catidad disponible o stock se debe obtener del campo cantidad en la tabla Articulos, mientras que la cantidad vendida se obtine del campo cantidad en la tabla Factura"
                . "- Cuando analices las compras de un cliente para poder recomendar art�culos, debes tener en cuenta la cantidad comprados historicamente, ya que ese es un indicador de que el cliente suele comprar ese articulos"
                . "- En tu analisis puedes incluir la cantidad de veces que se vendi� ese articulo ya que esa informaci�n determina que es un art�culo que se vende mucho. Siempre verifica que tengamos mas de 10 en Stock para recomendarlo"
                . "- Si la pregunta no es una consulta para verificar en la base de datos, por ejemplo Hola, puedes responder con un SELECT 'Cual es la pregunta' "
                . "- En los casos que la table contenga el cambo Detalle debe ser incluido en la consulta, para mostrar el mismo"
                . "- Asegura que las condiciones sin funciones de agregaci�n est�n en la cl�usula WHERE y las condiciones con funciones de agregaci�n est�n en la cl�usula HAVING. Verifica que no haya errores relacionados con el uso de estas cl�usulas."
                . "Ahora, por favor, genera la consulta SQL correspondiente a la siguiente pregunta:\n"
                . ". $consultaHumana .\n";
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
}
