<?php

namespace Donatella\Http\Controllers\Ia;

use Carbon\Carbon;
use Donatella\Models\ChatIAPedidos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class PedidosChat extends Controller
{
    public function chatIaIndex()
    {
        return view('test.consulta_ia');

    }
    public function chatIA()
    {
        // Tu clave API de OpenAI
        $api_key = config('services.openai.api_key');

        $consultaHumana = Input::get('consultaHumana');
        $id_cliente = Input::get('cliente_id');
        $id_pedido = Input::get('id_pedido');
        $id_user = Input::get('id_user');
        $this->guardaChat($id_pedido,$id_user,$consultaHumana);
        $consultaHumana = $consultaHumana . " para la clienta con id " . $id_cliente;
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
            //Estoy haciendo pruebas sin texto estructurado paso diectamente el json
            // $texto_estructurado = $this->estructuraDatos($consultaDB);
            $texto_estructurado = $consultaSQL;
            $question_respuesta = "Tu nombre es Mia y eres una asistente en ventas\n";
            $tipo = "Respuesta";
            $prompt_respuesta = $this->getPrompt($tipo,$texto_estructurado,$consultaHumana);
            $respuesta_data = $this->consultaApi($api_key,$question_respuesta,$prompt_respuesta);
            if (isset($respuesta_data['choices'][0]['message']['content'])) {
                $id_user = DB::Select('select id from samira.users where name="Mia"');
                $this->guardaChat($id_pedido,$id_user[0]->id,$respuesta_data['choices'][0]['message']['content']);
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
                . "1. clientes\n"
                . "    Campos: (`id_clientes`, `nombre`, `apellido`, `direccion`, `mail`, `telefono`, `cuit`, `provincia`, `localidad`, `apodo`, `id_provincia`, `updated_at`, `created_at`, `cant_ofertas`, `encuesta`, `codigopostal`)\n"
                . "    Funcion: Contiene los datos de todos los clientes.\n"
                . "2. Facturah\n"
                . "    Campos: (`Id`, `NroFactura`, `Total`, `Porcentaje`, `Descuento`, `Fecha`, `Estado`, `id_clientes`, `id_tipo_pago`, `envio`, `totalEnvio`, `id_estados_financiera`, `comentario`, `pagomixto`)\n"
                . "    Funcion: Contiene los datos de las facturas de los clientes.\n"
                . "3. Factura\n"
                . "    Campos: (`NroFactura`, `Articulo`, `Detalle`, `Cantidad`, `Descuento`, `Cajera`, `Vendedora`, `Fecha`, `Estado`, `ID`)\n"
                . "    Funcion: Contiene los artículos de cada factura y tiene relación con la tabla Facturah. Permite calcular el artículo más vendido.\n"
                . "4. Articulos\n"
                . "    Campos: (`Articulo`, `Detalle`, `Cantidad`,`Proveedor`, `Observaciones`, `Web`, `ImageName`, `websku`, `ProveedorSKU`, `CompraAuto`)\n"
                . "    Funcion: Contiene la información de todos los artículos.\n"
                . "6. Users\n"
                . "    Campos: (`id`, `name`, `email`, `created_at`, `updated_at`, `id_roles`, `codigo`, `foto`, `id_vendedoras`)\n"
                . "    Funcion: Contiene todos los usuarios y tiene relación con la tabla Vendedores.\n"
                . "8. ControlPedidos\n"
                . "    Campos: (`id`, `id_cliente`, `nropedido`, `vendedora`, `cajera`, `fecha`, `estado`, `nrofactura`, `total`, `ordenWeb`, `empaquetado`, `transporte`, `encuesta`, `ultactualizacion`, `local`, `totalweb`, `instancia`, `fecha_inicio_instancia`, `fecha_fin_instancia`, `fecha_proveedor`, `fecha_ultima_nota`, `pagado`, `fecha_pago`)\n"
                . "    Funcion: Contiene todos los pedidos realizados por los clientes. El campo `vendedora` hace referencia a quien armó el pedido y el campo `cajera` a quien lo facturó. Tiene relaciones con las tablas Facturah, Clientes, y Pedidotemp. Cada pedido tiene los siguientes estados: 0 - Facturado, 1 - Proceso, 2 - Cancelado.\n"
                . "9. Pedidotemp\n"
                . "    Campos: (`NroPedido`, `Articulo`, `Detalle`, `Cantidad`, `Descuento`, `Cajera`, `Vendedora`, `Fecha`, `Estado`, `ID`)\n"
                . "    Funcion: Contiene los ítems de cada pedido.\n"
                . "10. Registrollamadas\n"
                . "    Campos: (`id`, `users_id`, `clientes_id`, `comentarios`, `fecha`)\n"
                ."     Funcion: Contiene los registros de las notas de los clientes. Tiene relación con las tablas clientes y Users\n"
                . "Instrucciones:\n"
                . "- Cuando te haga una pregunta sobre los datos, genera solo la consulta SQL necesaria para obtener la información requerida.\n"
                . "- Asegúrate de que las consultas estén correctamente formateadas para MySQL.\n"
                . "- La respuesta debe comenzar directamente con la palabra 'SELECT'.\n"
                . "- Utiliza alias para las tablas y asegúrate de definir los alias correctamente en el `JOIN`.\n"
                . "- Si la pregunta no es una consulta para verificar en la base de datos, por ejemplo Hola, puedes responder con un SELECT 'Cual es la pregunta' "
                . "Ejemplos de Preguntas y Respuestas Esperadas:\n"
                . "**Pregunta:** \"¿Me puedes listar los clientes?\"\n"
                . "**Respuesta Esperada:** `SELECT * FROM clientes;`\n"
                . "**Pregunta:** \"¿Cuál es el total de todas las facturas?\"\n"
                . "**Respuesta Esperada:** `SELECT SUM(Total) FROM Facturah;`\n"
                . "**Pregunta:** \"¿Cuántos artículos hay en stock?\"\n"
                . "**Respuesta Esperada:** `SELECT COUNT(*) FROM Articulos WHERE Cantidad > 0;`\n"
                . "**Pregunta:** \"¿Puedes mostrarme los nombres y precios de todos los artículos?\"\n"
                . "**Respuesta Esperada:** `SELECT Articulo, PrecioConvertido FROM Articulos;`\n"
                . "**Pregunta:** \"¿Quién es el cliente con el ID 5?\"\n"
                . "**Respuesta Esperada:** `SELECT * FROM clientes WHERE id_clientes = 5;`\n"
                . "**Pregunta:** \"¿Cuántas compras realizaron los clientes en mayo de 2024?\"\n"
                . "**Respuesta Esperada:** `SELECT c.id_clientes, c.nombre, c.apellido, COUNT(*) AS total_compras FROM clientes c JOIN Facturah f ON c.id_clientes = f.id_clientes WHERE MONTH(f.Fecha) = 5 AND YEAR(f.Fecha) = 2024 GROUP BY c.id_clientes, c.nombre, c.apellido ORDER BY total_compras DESC;`\n"
                . "**Pregunta:** \"Cuantas compras realizo un determinado cliente \"\n"
                . "**Respuesta Esperada:** `SELECT  factura.Articulo as Articulo, factura.Detalle as Descripcion, sum(factura.Cantidad) as TotalVendido, factura.fecha, a.cantidad as Stock
                                FROM samira.facturah as facth
                                INNER JOIN samira.factura as factura ON facth.NroFactura = factura.NroFactura
                                INNER JOIN samira.articulos as a On a.Articulo = factura.articulo
                                where facth.id_clientes = 2021
                                GROUP BY factura.Articulo ORDER BY TotalVendido DESC;`"
                . "**Pregunta:** \"Basado en si historial de compras, que 5 articulos puedes recomendar que tengan mas de 10 en stock? \"\n"
                . "**Respuesta Esperada:** `SELECT a.Articulo, a.Detalle, a.Cantidad
                                                FROM Articulos a
                                                WHERE a.Cantidad > 10
                                                AND a.Articulo IN (
                                                    SELECT f.Articulo
                                                    FROM Factura f
                                                    JOIN Facturah fh ON f.NroFactura = fh.NroFactura
                                                    WHERE fh.id_clientes = 10854
                                                );`\n"
                . "**Pregunta:** \"¿Cuantos reclamos tiene la clienta con id 3412\"\n"
                . "**Respuesta Esperada:** `SELECT count(*) as TotalComentaroios FROM samira.registrollamadas where clientes_id = 3534;`\n"
                . "**Pregunta:** \"¿Que reclamos tiene la clienta con id 3412\"\n"
                . "**Respuesta Esperada:** `SELECT comentarios, fecha FROM samira.registrollamadas where clientes_id = 3534;`\n"
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
