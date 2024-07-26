<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Inversiones extends Model
{
    protected $table = 'inversiones';
    public $timestamps = false;
    protected $fillable = ['nbr_accion','recomendacion','dias_retencion','precio','fecha_compra','estado','porcentaje_ganancia',
                           'precio_venta','fecha_finalizacion','ganancia','informeia'];
}
