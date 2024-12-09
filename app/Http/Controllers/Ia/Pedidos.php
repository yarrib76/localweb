<?php

namespace Donatella\Http\Controllers\Ia;

use Carbon\Carbon;
use Donatella\Models\ChatIAPedidos;
use Illuminate\Database\QueryException;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class Pedidos extends Controller
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
        $id_cliente = Input::get('cliente_id');
        $id_pedido = Input::get('id_pedido');
        $id_user = Input::get('id_user');
        $this->guardaChat($id_pedido,$id_user,$consultaHumana);
        $consultaHumana = $consultaHumana . " para la clienta con id " . $id_cliente;
        $question_SQL = "�Cu�l es la consulta SQL necesaria para la siguiente pregunta: " .$consultaHumana . " La salida debe ser solo la consulta sql";
        $tipo = "Consulta";
        $texto_respuesta = "";
        $prompt = $this->getPrompt($tipo,$texto_respuesta,$consultaHumana);
        $asistenteSQL = new ChatGPT();
        /*Modelo 0 = gpt-4o-mini
          Modelo 1 = o1-mini Con razonammiento
        */
        $modelo = 0;
        $respuesta = $asistenteSQL->chatIA($question_SQL,$prompt,$modelo);
        $respuesta = str_replace(["```", "sql"], "",$respuesta);
        try {
            //Utilizo una conexion secundaria ya que el usuario de esta conexion solo tiene privilegios Select sobre la base de datos
            $consultaDB = DB::connection('mysql_secondary')->select($respuesta);
        } catch (QueryException $e) {
            return Response::json("Perdon,no entendi la pregunta, volver a consultar!!");
        }
        $texto_respuesta = json_encode($consultaDB);
        $question_respuesta = "Tu nombre es Mia y eres una asistente en ventas\n";
        $tipo = "Respuesta";
        $prompt_respuesta = $this->getPrompt($tipo,$texto_respuesta,$consultaHumana);
        $asistenteVentas = new ChatGPT();
        /*Modelo 0 = gpt-4o-mini
          Modelo 1 = o1-mini Con razonammiento
        */
        $modelo=0;
        $respuesta = $asistenteVentas->chatIA($question_respuesta,$prompt_respuesta,$modelo);
        $id_user = DB::Select('select id from samira.users where name="Mia"');
        $this->guardaChat($id_pedido,$id_user[0]->id,$respuesta);
        return Response::json($respuesta);
    }


    public function getPrompt($tipo,$texto_respuesta,$consultaHumana)
    {
        if ($tipo == "Consulta") {
            $prompt = "Basado en el esquema que te estoy proveyendo, escribe una consulta SQL que responda a las preguntas de los usuarios. Siempre utiliza los campos provistos en el esquema y aseg�rate de que la consulta comience directamente con la palabra 'SELECT', sin ning�n prefijo adicional. Aseg�rate tambi�n de definir correctamente los alias de las tablas cuando sea necesario.\n"
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
                . "    Funcion: Contiene los art�culos de cada factura y tiene relaci�n con la tabla Facturah. Permite calcular el art�culo m�s vendido.\n"
                . "4. Articulos\n"
                . "    Campos: (`Articulo`, `Detalle`, `Cantidad`,`Proveedor`, `Observaciones`, `Web`, `ImageName`, `websku`, `ProveedorSKU`, `CompraAuto`)\n"
                . "    Funcion: Contiene la informaci�n de todos los art�culos.\n"
                . "6. Users\n"
                . "    Campos: (`id` int(10), `name` varchar(255), `email` varchar(255), `created_at` timestamp, `updated_at` timestamp, `id_roles` int(11), `codigo` varchar(45), `foto` varchar(45), `id_vendedoras` int(11))\n"
                . "    Funcion: Contiene todos los usuarios y tiene relaci�n con la tabla Vendedores.\n"
                . "8. ControlPedidos\n"
                . "    Campos: (`id` int(11), `id_cliente` int(11), `nropedido` int(11), `vendedora` varchar(45), `cajera` varchar(45), `fecha` datetime, `estado` int(11), `nrofactura` int(11), `total` double, `ordenWeb` int(11), `empaquetado` int(2), `transporte` varchar(45), `encuesta` varchar(45), `ultactualizacion` datetime, `local` varchar(45), `totalweb` double, `instancia` int(2), `fecha_inicio_instancia` datetime, `fecha_fin_instancia` datetime, `fecha_proveedor` datetime, `fecha_ultima_nota` datetime, `pagado` int(2), `fecha_pago` datetime)\n"
                . "    Funcion: Contiene todos los pedidos realizados por los clientes. El campo `vendedora` hace referencia a quien arm� el pedido y el campo `cajera` a quien lo factur�. Tiene relaciones con las tablas Facturah, Clientes, y Pedidotemp. Cada pedido tiene los siguientes estados: 0 - Facturado, 1 - Proceso, 2 - Cancelado.\n"
                . "9. Pedidotemp\n"
                . "    Campos: (`NroPedido`, `Articulo`, `Detalle`, `Cantidad`, `Descuento`, `Cajera`, `Vendedora`, `Fecha`, `Estado`, `ID`)\n"
                . "    Funcion: Contiene los �tems de cada pedido.\n"
                . "10. Registrollamadas\n"
                . "    Campos: (`id`, `users_id`, `clientes_id`, `comentarios`, `fecha`)\n"
                ."     Funcion: Contiene los registros de las notas de los clientes. Tiene relaci�n con las tablas clientes y Users\n"
                . "Instrucciones:\n"
                . "- Cuando te haga una pregunta sobre los datos, genera solo la consulta SQL necesaria para obtener la informaci�n requerida.\n"
                . "- Aseg�rate de que las consultas est�n correctamente formateadas para MySQL.\n"
                . "- La respuesta debe comenzar directamente con la palabra 'SELECT'.\n"
                . "- Utiliza alias para las tablas y aseg�rate de definir los alias correctamente en el `JOIN`.\n"
                . "- Es muy importante que no confundas entre cantidad disponible y la cantidad comprada de un articulo. La catidad de disponible o stock se debe obtener del campo cantidad en la tabla Articulos, mientras que la cantidad vendida se obtine del campo cantidad en la tabla Factura"
                . "- Cuando analices las compras de un cliente para poder recomendar art�culos, debes tener en cuenta la cantidad comprados historicamente, ya que ese es un indicador de que el cliente suele comprar ese articulos"
                . "- En tu analisis puedes incluir la cantidad de veces que se vendi� ese articulo ya que esa informaci�n determina que es un art�culo que se vende mucho. Siempre verifica que tengamos mas de 10 en Stock para recomendarlo"
                . "- Si la pregunta no es una consulta para verificar en la base de datos, por ejemplo Hola, puedes responder con un SELECT 'Cual es la pregunta' "
                . "Ejemplos de Preguntas y Respuestas Esperadas:\n"
                . "**Pregunta:** \"�Me puedes listar los clientes?\"\n"
                . "**Respuesta Esperada:** `SELECT * FROM clientes;`\n"
                . "**Pregunta:** \"�Cu�l es el total de todas las facturas?\"\n"
                . "**Respuesta Esperada:** `SELECT SUM(Total) FROM Facturah;`\n"
                . "**Pregunta:** \"�Cu�ntos art�culos hay en stock?\"\n"
                . "**Respuesta Esperada:** `SELECT COUNT(*) FROM Articulos WHERE Cantidad > 0;`\n"
                . "**Pregunta:** \"�Puedes mostrarme los nombres y precios de todos los art�culos?\"\n"
                . "**Respuesta Esperada:** `SELECT Articulo, PrecioConvertido FROM Articulos;`\n"
                . "**Pregunta:** \"�Qui�n es el cliente con el ID 5?\"\n"
                . "**Respuesta Esperada:** `SELECT * FROM clientes WHERE id_clientes = 5;`\n"
                . "**Pregunta:** \"�Cu�ntas compras realizaron los clientes en mayo de 2024?\"\n"
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
                . "**Pregunta:** \"�Cuantos reclamos tiene la clienta con id 3412\"\n"
                . "**Respuesta Esperada:** `SELECT count(*) as TotalComentaroios FROM samira.registrollamadas where clientes_id = 3534;`\n"
                . "**Pregunta:** \"�Que reclamos tiene la clienta con id 3412\"\n"
                . "**Respuesta Esperada:** `SELECT comentarios, fecha FROM samira.registrollamadas where clientes_id = 3534;`\n"
                . "Ahora, por favor, genera la consulta SQL correspondiente a la siguiente pregunta:\n"
                . ". $consultaHumana .\n";
            return $prompt;
        } else {
            $prompt_respuesta ="Informaci�n de Respuesta: ". $texto_respuesta . "\n\n"  // Aseg�rate de que $consultaSQL contiene el resultado en formato JSON
                . "Pregunta original del usuario: " . $consultaHumana . "\n\n"
                . "Proporciona una respuesta en lenguaje natural basada en al informaci�n provista.\n"
                . "Es fundamental para el area de ventas que las respuesta que tu brindes ayuden a nuestras vendedoras a potenciar las ventas.\n"
                //. "En caso que la informaci�n no devuelva nung�n resultado, responder no hay resultados para su consulta."
                . "No responder informaci�n relacioanada a ganancias.\n"
                . "Nuestra moneda es el peso.\n"
                . "No incluir el id_cliente del cliente en la respuesta\n"
                . "Finaliza tu respuesta con: '�Te puedo ayudar en alguna otra cosa?'\n";
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
