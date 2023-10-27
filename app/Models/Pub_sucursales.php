<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Pub_sucursales extends Model
{
    protected $table = 'pub_sucursales';
    protected $fillable = ['id_provincias','codigo_provincias','codigo_sucursal','nombre_sucursal'];
    public $timestamps = false;
}
