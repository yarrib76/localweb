<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class FacturacionHist extends Model
{
    protected $table = 'facturah';
    protected $fillable = ['Id','NroFactura','Total', 'Porcentaje', 'Descuento', 'Ganancia', 'Fecha', 'Estado'];
}
