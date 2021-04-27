<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Pagos extends Model
{
    protected $table = 'tipo_pagos';
    protected $fillable = ['id_tipo_pagos','tipo_pago'];
    public $timestamps = false;
}
