<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Promociones extends Model
{
    protected $table = 'promocion';
    public $timestamps = false;
    protected $fillable = ['id_cliente','fecha_creacion','fecha_vencimiento','fecha_cierre','estado',
                            'nrofactura','detalle','codautorizacion'];
}
