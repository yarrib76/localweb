<?php

namespace Donatella\Http\Controllers\Problemas;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Ganancias extends Controller
{
    public function run()
    {
        //Solo consulta si hay divrencias de ganancias entre Factura y FacturaH
        //$facturasErroneas = $this->query();
        //Resuelve el problema de las diferencias. Comentar después de utilizar
        $facturasErroneas = $this->resolveIssue();
        return $facturasErroneas;

       /*
        Verifico si Ganancia de FacturaH es Igual a Ganancia de Factura:
        Si No tiene descuenro{
        Ganancia FacturaH = GananciaFactura
}

        Si Tiene descuento:


        Si No tiene descuenro{
        (Ganancia FacturaH = GananciaFactura)
		Verdadero : esta OK
        Falso: Lo resuelvo asi : Restar Total de FacturaH - Total de SUM(PrecioArgen * Cantidad) de Factura = Ganancia Real}

        Si tiene descuento {
        (Ganancia FacturaH = (DescuentoFacturasH - Total de SUM(PrecioArgen * Cantidad) de Factura)
	    Verdadero : esta OK
        Falso: Lo resuevo asi : Restar Descuento de FacturaH - Total de SUM(PrecioArgen * Cantidad) de Factura = Ganancia Real
}*/

    }
    public function query()
    {
        $facturasErroneas [] = "";
        $x= 0;
        $facturas = DB::select('SELECT nrofactura, ROUND(SUM(PrecioArgen * Cantidad),2) as PrecioArgen ,ROUND(SUM(Ganancia),0) as GananciaTotal
                              FROM samira.factura
                              GROUP BY nrofactura;');
        foreach ($facturas as $factura){
            $facturaH = DB::select('SELECT nrofactura, Total, Porcentaje, descuento, ROUND(ganancia,0) as Ganancia FROM samira.facturah
                                    where nrofactura = "'.$factura->nrofactura.'";');
            //Verifico is la factura tuvo descuento (Si el campo "$facturaH[0]->descuento" = Null No tuvo descuento
            if (is_null($facturaH[0]->descuento)){
                if ($factura->GananciaTotal != $facturaH[0]->Ganancia){
                    $facturasErroneas[$x] = ['NroFactura' => $factura->nrofactura,'GananciaTotal' => $factura->GananciaTotal,
                        'GananciaFactH' => $facturaH[0]->Ganancia];
                }
            //Si Tuvo descuento realizo esta operación
            }else {
                if ($facturaH[0]->Ganancia != round(($facturaH[0]->descuento - $factura->PrecioArgen),0))
                    $facturasErroneas[$x] = ['NroFactura' => $factura->nrofactura,'GananciaTotal' => round(($facturaH[0]->descuento - $factura->PrecioArgen),0),
                        'GananciaFactH' => $facturaH[0]->Ganancia , 'Descuento' => 'SI'];
            }
            $x++;
        }
        return $facturasErroneas;
    }
    public function resolveIssue()
    {
        $facturasErroneas [] = "";
        $x= 0;
        $facturas = DB::select('SELECT nrofactura, ROUND(SUM(PrecioArgen * Cantidad),2) as PrecioArgen ,ROUND(SUM(Ganancia),0) as GananciaTotal
                              FROM samira.factura
                              GROUP BY nrofactura;');
        foreach ($facturas as $factura){
            $facturaH = DB::select('SELECT nrofactura, Total, Porcentaje, descuento, ROUND(ganancia,0) as Ganancia FROM samira.facturah
                                    where nrofactura = "'.$factura->nrofactura.'";');
            //Verifico is la factura tuvo descuento (Si el campo "$facturaH[0]->descuento" = Null No tuvo descuento
            if (is_null($facturaH[0]->descuento)){
                if ($factura->GananciaTotal != $facturaH[0]->Ganancia){
                    DB::select('UPDATE samira.facturah SET Ganancia = "'.$factura->GananciaTotal.'"
                        WHERE nrofactura = "'.$factura->nrofactura.'"');
                }
            //Si Tuvo descuento realizo esta operación
            }else {
                if ($facturaH[0]->Ganancia != round(($facturaH[0]->descuento - $factura->PrecioArgen),0))
                    $facturasErroneas[$x] = ['NroFactura' => $factura->nrofactura,'GananciaTotal' => round(($facturaH[0]->descuento - $factura->PrecioArgen),0),
                        'GananciaFactH' => $facturaH[0]->Ganancia , 'Descuento' => 'SI'];
                    DB::select('UPDATE samira.facturah SET Ganancia = "'.round(($facturaH[0]->descuento - $factura->PrecioArgen),0).'"
                        WHERE nrofactura = "'.$factura->nrofactura.'"');
            }
            $x++;
        }
        return "Finish";
    }
}
