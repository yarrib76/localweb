<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class StatusReportes extends Model
{
    protected $table = 'statusreportes';
    public $timestamps = false;
    protected $fillable = ['Reporte','Fecha'];
}
