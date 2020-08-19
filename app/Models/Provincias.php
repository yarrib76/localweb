<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Provincias extends Model
{
    protected $table = 'provincias';
    protected $fillable = ['id','nombre'];
}
