<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class StatusEcomerceSinc extends Model
{
    protected $table = 'statusecomercesincro';
    public $timestamps = false;
    protected $fillable = ['id','id_provecomerce','status','fecha','articulo','product_id','articulo_id','visible','images','imagessrc'];
}
