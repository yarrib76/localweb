<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ProvEcomerce extends Model
{
    protected $table = 'provecomerce';
    public $timestamps = false;
    protected $fillable = ['id','proveedor','id_users','fecha'];
}
