<?php

namespace Donatella\Http\Controllers\Api\Bi;

use Carbon\Carbon;
use Donatella\Models\Provincias;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class MapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index()
    {
        $año = Input::get('año');
        if (is_null($año)){
            $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        }
        $datos = DB::select('select prov.nombre as Provincia, round(sum(facth.total),2) as Total, prov.id as Prov_id, round(sum((Total * 100) / (select round(sum(facth.total),2) from samira.facturah as facth
                            inner join samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                            inner join samira.provincias as prov ON prov.id = cli.id_provincia
                            where prov.nombre <> "Otro" and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31")),2) as Porcentaje
                            from samira.facturah as facth
                            inner join samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                            inner join samira.provincias as prov ON prov.id = cli.id_provincia
                            where prov.nombre <> "Otro" and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31"
                            GROUP BY prov.nombre ORDER BY Total DESC');
        return view('bi.mapa',compact('año','datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function datos()
    {
       // $año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        $año = Input::get('año');
        $datos = DB::select('select prov.nombre as Provincia, sum(facth.total) as Total, prov.id as Prov_id
                            from samira.facturah as facth
                            inner join samira.clientes as cli ON cli.id_clientes = facth.id_clientes
                            inner join samira.provincias as prov ON prov.id = cli.id_provincia
                            where prov.nombre <> "Otro" and facth.Fecha >= "' . $año .'/01/01" and facth.Fecha <= "' . $año .'/12/31"
                            GROUP BY prov.nombre ORDER BY Total DESC;');
        $info=[];
        $datos = $this->conviertoProvincias($datos);
        foreach ($datos as $dato){
            //$info[$dato['Provincia']] = ['value' => $dato['Total'], 'tooltip'=>['content' => $dato['Provincia'] . " $" . $dato['Total']]];
            $info[$dato['Provincia']] = ['value' => $dato['Total'],'id_provincia' => $dato['Prov_id'], 'tooltip'=>['content' => $dato['Provincia'] . " $" . $dato['Total']]];
        }

       // $datos['tierradelfuego'] = ['value' => '2000', 'tooltip'=> ['content' => '5000' ]];
     //   $datos['santacruz'] = ['value' => '2268265', 'href:' => '#'];
    //    json = [{"tierradelfuego":{ "value": "2000", "href": "#","tooltip":{"content": "Facturacion 3000"}}}];

        return json_encode($info);
    }

    public function rankClientes()
    {
        //$año = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->year;
        $año = Input::get('año');
        $provincia_id = Input::get('provincia_id');
        $rankCLiente = DB::select('SELECT CONCAT (cli.nombre, "," , cli.apellido) as Cliente, sum(fach.Total) as Total,
                                    cli.localidad as Localidad, prov.nombre as Provincia
                                    FROM samira.facturah as fach
                                    INNER JOIN samira.clientes as cli ON fach.id_clientes = cli.id_clientes
                                    INNER JOIN samira.provincias as prov ON cli.id_provincia = prov.id
                                    where prov.id = " ' . $provincia_id .'" and fach.Fecha >= "' . $año .'/01/01" and fach.Fecha <= "' . $año .'/12/31"
                                    GROUP BY Cliente ORDER BY Total DESC;');

        return Response::json($rankCLiente);
    }

    function conviertoProvincias ($datos){
        $i = 0;
        foreach ($datos as $dato){
            switch ($dato->Provincia){
                case "Buenos Aires": $datoConvertido[$i] = ["Provincia" => "bsas", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Catamarca": $datoConvertido[$i] = ["Provincia" => "catamarca", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Chaco": $datoConvertido[$i] = ["Provincia" => "chaco", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Chubut": $datoConvertido[$i] = ["Provincia" => "chubut", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Córdoba": $datoConvertido[$i] = ["Provincia" => "cordoba", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Corrientes": $datoConvertido[$i] = ["Provincia" => "corrientes", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Entre Ríos": $datoConvertido[$i] = ["Provincia" => "entrerios", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Formosa": $datoConvertido[$i] = ["Provincia" => "formosa", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Jujuy": $datoConvertido[$i] = ["Provincia" => "jujuy", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "La Pampa": $datoConvertido[$i] = ["Provincia" => "lapampa", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "La Rioja": $datoConvertido[$i] = ["Provincia" => "larioja", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Mendoza": $datoConvertido[$i] = ["Provincia" => "mendoza", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Misiones": $datoConvertido[$i] = ["Provincia" => "misiones", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Neuquén": $datoConvertido[$i] = ["Provincia" => "neuquen", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Río Negro": $datoConvertido[$i] = ["Provincia" => "rionegro", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Salta": $datoConvertido[$i] = ["Provincia" => "salta", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "San Juan": $datoConvertido[$i] = ["Provincia" => "sanjuan", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "San Luis": $datoConvertido[$i] = ["Provincia" => "sanluis", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Santa Cruz": $datoConvertido[$i] = ["Provincia" => "santacruz", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Santa Fe": $datoConvertido[$i] = ["Provincia" => "santafe", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Santiago del Estero": $datoConvertido[$i] = ["Provincia" => "santiago", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Tierra del Fuego": $datoConvertido[$i] = ["Provincia" => "tierradelfuego", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
                case "Tucumán": $datoConvertido[$i] = ["Provincia" => "tucuman", "Total" => $dato->Total, "Prov_id" => $dato->Prov_id];
                    break;
            }
            $i++;
        }
        return $datoConvertido;
    }
}
