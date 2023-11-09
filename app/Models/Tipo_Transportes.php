<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Transportes extends Model
{
    protected $table = 'transportes';
    protected $fillable = ['id_transportes','nombre'];
    public $timestamps = false;
}
