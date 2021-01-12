<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenArticulos extends Model
{
    protected $table = 'ordenesarticulos';
    public $timestamps = false;
    protected $fillable = ['articulo','detalle','precio','cantidad','id_controlpedidos'];
}
