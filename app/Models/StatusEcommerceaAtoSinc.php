<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class StatusEcommerceaAtoSinc extends Model
{
    protected $table = 'statusecommerceautosinc';
    public $timestamps = false;
    protected $fillable = ['id','articulo','fecha','stock'];
}
