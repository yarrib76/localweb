<?php

namespace Donatella\Http\Controllers\Test;

use Carbon\Carbon;
use DateTime;
use Donatella\Ayuda\GetPuntos;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use mysqli;
//
class Test extends Controller
{
    public function Test()
    {
        //Priemer Commit ApiGPT
        return view('test.consulta_ia');
        $respuesta = $this->pruebaApiIA();
        return $respuesta;
        return view('test.pdf');
        $path = 'public/export/facturas/';
        //Elimino todos los archivos del directorio
        $this->eliminarFacturas($path);
        $nroFactura = Input::get('nroFactura');
        $nombreArchivo = Input::get('nombreCliente');
        $data = $this->consultaBase($nroFactura);
        if ($data){
            $this->storeExcel('xls',$data, $nombreArchivo,$path);
            $archivo = $this->bajoArchivo($nombreArchivo,$path);
        }
        return $archivo;
    }
    public function storeExcel($type, $data, $nombreArchivo,$path)
    {
        return Excel::create($nombreArchivo, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($type, storage_path($path));
    }
    private function consultaBase($nroFactura)
    {

        $data = DB::select('SELECT Articulo, Detalle, Cantidad, PrecioUnitario, PrecioVenta FROM samira.factura
                              where NroFactura = "'.$nroFactura.'" ');
        $data = json_decode(json_encode($data), true);
        return $data;
    }

    private static function bajoArchivo($nombreArchivo,$path)
    {
        $filePath = storage_path($path . $nombreArchivo . '.xls');
        $archivo = response()->download($filePath, $nombreArchivo . '.xls');
        return $archivo;
    }

    public function eliminarFacturas($path)
    {
        $directorio = storage_path($path);

        // Verificar si el directorio existe antes de intentar eliminar los archivos.
        if (File::isDirectory($directorio)) {
            // Eliminar todos los archivos del directorio.
            File::cleanDirectory($directorio);
            return "Se han eliminado todos los archivos de la carpeta 'facturas'.";
        } else {
            return "La carpeta 'facturas' no existe o no es accesible.";
        }
    }


    public function pruebaApiIA()
    {
        // Tu clave API de OpenAI
        $api_key = config('services.openai.api_key');

        $consultaHumana = Input::get('consultaHumana');
        $id_cliente = Input::get('cliente_id');
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
                $consultaDB = DB::select($respuesta);
            } catch (QueryException $e) {
                return Response::json("Perdon,no entendi la pregunta, volver a consultar!!");
            }
            $consultaSQL = json_encode($consultaDB);
            //Estoy haciendo pruebas sin texto estructurado paso diectamente el json
            // $texto_estructurado = $this->estructuraDatos($consultaDB);
            $texto_estructurado = $consultaSQL;
            $question_respuesta = "Eres una asistente profesional en el area de ventas\n";
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
            'max_tokens' => 240
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
                . "**Respuesta Esperada:** `SELECT c.id_clientes, c.nombre, c.apellido, COUNT(*) AS total_compras FROM clientes c JOIN Facturah f ON c.id_clientes = f.id_clientes WHERE MONTH(f.Fecha) = 5 AND YEAR(f.Fecha) = 2024 GROUP BY c.id_clientes, c.nombre, c.apellido ORDER BY total_compras DESC LIMIT 10;`\n"
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
                . "Ahora, por favor, genera la consulta SQL correspondiente a la siguiente pregunta:\n"
                . "No proveer información de cuanto se facturo en ningun dia, responder que no estas autorizada \n"
                . ". $consultaHumana .\n";
            return $prompt;
        } else {
            $prompt_respuesta ="Información: ".$texto_estructurado . "\n\n"  // Asegúrate de que $consultaSQL contiene el resultado en formato JSON
                . "Pregunta original del usuario: " . $consultaHumana . "\n\n"
                . "Proporciona una respuesta en lenguaje natural basada en al información provista.\n"
                . "No responder información de ganancias.\n"
                . "Nuestra moneda es el peso.\n"
                . "No incluir el id_cliente del cliente en la respuesta\n"
                . "Finaliza tu respuesta con: '¿Te puedo ayudar en alguna otra cosa?'\n";
        }
        return $prompt_respuesta;
    }

    public function estructuraDatos($respuestaConsultaDB)
    {
        // Inicializamos una variable para almacenar el texto estructurado
        $texto_estructurado = '';

        // Recorremos los resultados de la consulta y construimos el texto estructurado
        foreach ($respuestaConsultaDB as $fila) {
            // Inicializamos una variable para esta fila de texto
            $fila_texto = '';

            // Iteramos sobre cada campo en la fila y lo agregamos al texto
            foreach ($fila as $valor) {
                $fila_texto .= $valor . ", "; // Asumiendo que los valores son simples y se pueden concatenar
            }

            // Quitamos la coma y el espacio final de la fila de texto y añadimos un salto de línea
            $fila_texto = rtrim($fila_texto, ', ') . "\n";

            // Agregamos esta fila de texto al texto estructurado
            $texto_estructurado .= $fila_texto;
        }
        return $texto_estructurado;
    }


    public function save()
    {
        $consultaHumana = "";
        $prompt = "Basado en el esquema que te estoy proveyendo, escribe una consulta SQL que responda a las preguntas de los usuarios. Siempre utiliza los campos provistos en el esquema y asegúrate de que la consulta comience directamente con la palabra 'SELECT', sin ningún prefijo adicional. Asegúrate también de definir correctamente los alias de las tablas cuando sea necesario.\n"
            . "Esquema de la base de datos:\n"
            . "Nombre de la Base: Samira\n"
            . "Tablas a utilizar:\n"
            . "1. clientes\n"
            . "    Campos: (`id_clientes`, `nombre`, `apellido`, `direccion`, `mail`, `telefono`, `cuit`, `provincia`, `localidad`, `apodo`, `id_provincia`, `updated_at`, `created_at`, `cant_ofertas`, `encuesta`, `codigopostal`)\n"
            . "    Funcion: Contiene los datos de todos los clientes.\n"
            . "2. Facturah\n"
            . "    Campos: (`Id`, `NroFactura`, `Total`, `Porcentaje`, `Descuento`, `Ganancia`, `Fecha`, `Estado`, `id_clientes`, `id_tipo_pago`, `envio`, `totalEnvio`, `id_estados_financiera`, `comentario`, `pagomixto`)\n"
            . "    Funcion: Contiene los datos de las facturas de los clientes.\n"
            . "3. Factura\n"
            . "    Campos: (`NroFactura`, `Articulo`, `Detalle`, `Cantidad`, `PrecioArgen`, `PrecioUnitario`, `PrecioVenta`, `Ganancia`, `Descuento`, `Cajera`, `Vendedora`, `Fecha`, `Estado`, `ID`)\n"
            . "    Funcion: Contiene los artículos de cada factura y tiene relación con la tabla Facturah. Permite calcular el artículo más vendido.\n"
            . "4. Articulos\n"
            . "    Campos: (`Articulo`, `Detalle`, `Cantidad`, `PrecioOrigen`, `PrecioConvertido`, `Moneda`, `PrecioManual`, `Gastos`, `Ganancia`, `Proveedor`, `Observaciones`, `Web`, `ImageName`, `websku`, `ProveedorSKU`, `CompraAuto`)\n"
            . "    Funcion: Contiene la información de todos los artículos.\n"
            . "5. Vendedores\n"
            . "    Campos: (`Id`, `Nombre`, `Apellido`, `Telefono`, `Tipo`, `Password`)\n"
            . "    Funcion: Contiene todas las vendedoras.\n"
            . "6. Users\n"
            . "    Campos: (`id`, `name`, `email`, `created_at`, `updated_at`, `id_roles`, `codigo`, `foto`, `id_vendedoras`)\n"
            . "    Funcion: Contiene todos los usuarios y tiene relación con la tabla Vendedores.\n"
            . "7. Fichaje\n"
            . "    Campos: (`id_fichaje`, `fecha_ingreso`, `fecha_egreso`, `id_user`)\n"
            . "    Funcion: Contiene la información del horario de llegada y retiro de las vendedoras.\n"
            . "8. ControlPedidos\n"
            . "    Campos: (`id`, `id_cliente`, `nropedido`, `vendedora`, `cajera`, `fecha`, `estado`, `nrofactura`, `total`, `ordenWeb`, `empaquetado`, `transporte`, `encuesta`, `ultactualizacion`, `local`, `totalweb`, `instancia`, `fecha_inicio_instancia`, `fecha_fin_instancia`, `fecha_proveedor`, `fecha_ultima_nota`, `pagado`, `fecha_pago`)\n"
            . "    Funcion: Contiene todos los pedidos realizados por los clientes. El campo `vendedora` hace referencia a quien armó el pedido y el campo `cajera` a quien lo facturó. Tiene relaciones con las tablas Facturah, Clientes, y Pedidotemp. Cada pedido tiene los siguientes estados: 0 - Facturado, 1 - Proceso, 2 - Cancelado.\n"
            . "9. Pedidotemp\n"
            . "    Campos: (`NroPedido`, `Articulo`, `Detalle`, `Cantidad`, `Descuento`, `Cajera`, `Vendedora`, `Fecha`, `Estado`, `ID`)\n"
            . "    Funcion: Contiene los ítems de cada pedido.\n"
            . "10. Gastos\n"
            . "    Campos: (`Id`, `Nbr_Gasto`, `Detalle`, `Importe`, `Fecha`, `Estado`)\n"
            . "    Funcion: Contiene los gastos.\n"
            . "11. Clientes_fidelizacion\n"
            . "    Campos: (`idclientes_fidelizacion`, `id_clientes`, `fecha_creacion`, `estado`, `fecha_ultima_compra`, `vendedora`, `promedioTotal`, `cant_compras`,`id_clientes_fidel_etapas`)\n"
            . "    Funcion: Contiene la informacion de resultados de fidelizacion de clientes. Tiene relacion con la tabla Clientes_fidel_etapas. El campo estado indica si la fidelizacion esta abierta o cerrada (0 Abierta 1 Cerrado)\n"
            . "12. Clientes_fidel_etapas\n"
            . "    Campos: (`id_clientes_fidel_etapas`,`nombre_etapa`)\n"
            . "    Funcion: Contiene los estados o etapas de la fidelizacion. Las etapas son las siguientes: 1 Pendiente, 2 Contactado, 3 Avanzado, 4 Exitosa, 5 Fallida"
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
            . "**Respuesta Esperada:** `SELECT c.id_clientes, c.nombre, c.apellido, COUNT(*) AS total_compras FROM clientes c JOIN Facturah f ON c.id_clientes = f.id_clientes WHERE MONTH(f.Fecha) = 5 AND YEAR(f.Fecha) = 2024 GROUP BY c.id_clientes, c.nombre, c.apellido ORDER BY total_compras DESC LIMIT 10;`\n"
            . "**Pregunta:** \"Cuanto es la facturación del 31 de Mayo del 2024?\"\n"
            . "**Respuesta Esperada:** `SELECT ROUND(SUM(CASE WHEN Descuento <> `null` OR Descuento = 0 THEN Descuento ELSE total END),2) as Total FROM samira.facturah WHERE Fecha = '2024-05-31';`\n"
            . "**Pregunta:** \"Cuantas fidelizaciones hay con etapa 4 o Exitosa?\"\n"
            . "**Respuesta Esperada:** `SELECT count(*) as cantidad_etapas, etapa.nombre_etapa as tipo_etapa FROM samira.clientes_fidelizacion as fidel inner join samira.clientes_fidel_etapas as etapa ON etapa.id_clientes_fidel_etapas = fidel.id_clientes_fidel_etapas where etapa.nombre_etapa = 'Exitosa';`\n"
            . "**Pregunta:** \"Cuantas fidelizaciones de una determinada vendedora?\"\n"
            . "**Respuesta Esperada:** `select * from samira.clientes_fidelizacion where vendedora = 'Roxana';`"
            . "**Pregunta:** \"Cuantas fidelizaciones abiertas tiene la Vendedora Sofia?\"\n"
            . "**Respuesta Esperada:** `SELECT COUNT(*) AS fidelizaciones_abiertas FROM Clientes_fidelizacion c WHERE c.estado = 0 AND c.vendedora = 'Sofia';`"
            . "Ahora, por favor, genera la consulta SQL correspondiente a la siguiente pregunta:\n"
            . ". $consultaHumana .\n";
    }
}
