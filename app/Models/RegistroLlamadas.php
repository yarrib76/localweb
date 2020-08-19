<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroLlamadas extends Model
{
    protected $table = 'registrollamadas';
    public $timestamps = false;
    protected $fillable = ['users_id','clientes_id','comentarios', 'fecha'];
}
