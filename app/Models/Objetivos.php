<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Objetivos extends Model
{
    protected $table = 'objetivos';
    protected $fillable = ['mes','fich_obj','fich_alcance','ped_obj','ped_alcance','v_salon_obj',
                            'v_salon_alcance','cancel_obj','cancel_alcance','no_encuesta_obj',
                            'no_encuesta_alcance','fidel_obj','fidel_alcance','id_users'];
    public $timestamps = false;
}
