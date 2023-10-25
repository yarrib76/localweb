<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class FichajeDB extends Model
{
    protected $table = 'fichaje';
    protected $fillable = ['fecha_ingreso','fecha_egreso','id_user'];
    public $timestamps = false;
}
