<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Notas_Adhesivas extends Model
{
    protected $table = 'notas_adhesivas';
    public $timestamps = false;
    protected $fillable = ['id_notas_adhesivas,titulo,body,id_rolesweb'];
}
