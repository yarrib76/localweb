<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Estados_Financiera extends Model
{
    protected $table = 'estados_financiera';
    protected $fillable = ['id_estados','nombre'];
    public $timestamps = false;
}
