<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompras extends Model
{
    protected $table = 'ordencompras';
    public $timestamps = false;
    protected $fillable = ['NumeroOrden'];
}
