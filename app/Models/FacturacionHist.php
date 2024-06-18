<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class FacturacionHist extends Model
{
    protected $table = 'facturah';
    public $timestamps = false;
    protected $fillable = ['Id','NroFactura','Total', 'Porcentaje', 'Descuento',
        'Ganancia', 'Fecha', 'Estado','id_clientes', 'envio', 'totalEnvio', 'id_tipo_pago', 'id_estados_financiera','comentario','pagomixto','vendedora'];
}
