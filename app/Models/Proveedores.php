<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table = 'proveedores';
    protected $fillable = ['Nombre','Gastos','Ganancia'];
}
