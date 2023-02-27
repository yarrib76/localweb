<?php

namespace Donatella\Http\Controllers\ClientesFidelizacion;

use Carbon\Carbon;
use Donatella\Models\Cliente_Fidelizacion;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Donatella\Models\Notas_Clientes_Fidel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ClientesFidel extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        return view('clientesfidel.reporte', compact('user_id'));
    }

    public function cargoClientesFidel()
    {
        $conexion = $this->creoConnect();
        $montoMinimo = 22000;
        $cant_meses = 3;
        //Llamo al StoreProcedure y traigo los datos
        $r = $conexion->query('CALL cursor_clientes_fidelizacion("'. $montoMinimo .'","1000000","'.$cant_meses.'")');
        while ($row = mysqli_fetch_array($r)) {
            $res[] = $row;
        }
        $count = 0;
        foreach ($res as $respuesta) {
            $query  = DB::select('select id_clientes, max(fecha_creacion) as Fecha_Creacion, estado from samira.clientes_fidelizacion
                              where id_clientes = "'. $respuesta['id'] .'"
                              group by id_clientes
                              having fecha_creacion >= DATE_SUB(NOW(),INTERVAL 3 MONTH);');
            if (!$query){
                $count++;
                if ($count <= 5){
                    DB::select('INSERT INTO samira.clientes_fidelizacion (id_clientes,fecha_ultima_compra,fecha_creacion,promedioTotal,cant_compras)
                        VALUE("'. $respuesta['id'] .'","'.$respuesta['Fecha'].'",now(),"'.$respuesta['PromedioTotal'].'","'.$respuesta['CantCompras'].'")');
                } else {break;}
            }
        }

        dd('Listo');

    }

    private function creoConnect()
    {
        try {
            $con = new \mysqli(env("DB_HOST", "localhost"), env("DB_USERNAME", "root"), env("DB_PASSWORD", 'NetAcc10'), env("DB_DATABASE", "samira"))
            or die('Could not connect to the database server' . mysqli_connect_error());
            if (empty($con))
                throw new \Exception("Connection is only allowed for authorized personnels.", 5001);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return $con;
    }

    public function query()
    {
        $estado = (Input::get('estado'));
        $clientesFidel = DB::select('SELECT idclientes_fidelizacion, concat(cliente.nombre, ",",cliente.apellido) as Cliente, telefono as cel_contacto, fecha_creacion, fecha_ultima_compra,
                                      vendedora,(select count(*) from samira.notas_clientes_fidel
                                      where id_clientes_fidelizacion = idclientes_fidelizacion) as cant_notas, promedioTotal, cant_compras  FROM samira.clientes_fidelizacion as fidel
                                      inner join samira.clientes as cliente ON cliente.id_clientes = fidel.id_clientes
                                      WHERE estado = "'.$estado.'";');
        ob_start('ob_gzhandler');
        return Response::json($clientesFidel);
    }
    public function vendedoras()
    {
        $arrVendedoras = [];
        $vendedoras = Vendedores::where('Tipo', '<>', 0)->get();
        for ($i = 0; $i < $vendedoras->count(); $i++ ){
            $arrVendedoras[$i] = [$vendedoras[$i]->Nombre => $vendedoras[$i]->Nombre];
        }
        ob_start('ob_gzhandler');
        return Response::json($arrVendedoras);
    }

    public function updateVendedora()
    {
        $datos = Input::all();
        $articulo = Cliente_Fidelizacion::where('idclientes_fidelizacion', $datos['idclientes_fidelizacion']);
        $articulo->update([
            'vendedora' => $datos['vendedora']
        ]);
        return;
    }

    public function notas()
    {
        $idclientes_fidelizacion = Input::get('idclientes_fidelizacion');
        DB::statement("SET lc_time_names = 'es_ES'");
        $notas = DB::select('SELECT DATE_FORMAT(fecha_creacion, "%d de %M %Y %k:%i") AS fechaFormateada, usuarios.name as nombre,
                                        notasFidel.notas as comentario
                                        from samira.notas_clientes_fidel as notasFidel
                                        INNER JOIN samira.users as usuarios ON usuarios.id = notasFidel.users_id
                                        WHERE notasFidel.id_clientes_fidelizacion = "'. $idclientes_fidelizacion . '"
                                        ORDER BY fecha_creacion DESC');
        return Response::json($notas);
    }


    public function finalizarClienteFidel()
    {
        $id_cliente_fidel = Input::get('idclientes_fidelizacion');
        $carrito = Cliente_Fidelizacion::where('idclientes_fidelizacion',$id_cliente_fidel);
        $carrito->update([
            'estado' => 1,
        ]);
        return;
    }

    public function agregarNotas()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        Notas_Clientes_Fidel::create([
            'id_clientes_fidelizacion' => Input::get('idclientes_fidelizacion'),
            'users_id' => Input::get('user_id'),
            'notas' => Input::get('textarea'),
            'fecha_creacion' => $fecha
        ]);
        return Response::json('ok');
    }

    public function biFidel(){
        $idclientes_fidelizacion = Input::get('idclientes_fidelizacion');
        $cliente_id= DB::select('select id_clientes from samira.clientes_fidelizacion
                                 where idclientes_fidelizacion = "'.$idclientes_fidelizacion.'"');
        $clienteArticulos = DB::select('SELECT  factura.Articulo as Articulo, factura.Detalle as Descripcion, sum(factura.Cantidad) as Total
                            FROM samira.facturah as facth
                            INNER JOIN samira.factura as factura
                            ON facth.NroFactura = factura.NroFactura
                            where facth.id_clientes = "'. $cliente_id[0]->id_clientes .'"
                            GROUP BY factura.Articulo ORDER BY Total DESC
                            limit 10;');

        return Response::json($clienteArticulos);
    }
}

