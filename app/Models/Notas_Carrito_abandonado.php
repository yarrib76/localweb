<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Notas_Carrito_abandonado extends Model
{
    protected $table = 'notas_carritos_abandonados';
    public $timestamps = false;
    protected $fillable = ['id_notas_carritos_abandonados','id_carritos_abandonados','fecha','notas','users_id'];
}
