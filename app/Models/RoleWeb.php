<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class RoleWeb extends Model
{
    protected $table = 'rolesweb';
    public $timestamps = false;
    protected $fillable = ['id_roles','tipo_role'];
}
