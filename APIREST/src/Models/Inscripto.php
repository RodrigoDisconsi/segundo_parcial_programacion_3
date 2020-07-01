<?php
namespace App\Models;

class Inscripto extends \Illuminate\Database\Eloquent\Model {
    public $timestamps = false;

    public function alumno()
    {
        return $this->belongsTo('App\Models\User', 'alumno_id');
    }

    // public function materia()
    // {
    //     return $this->belongsTo('App\Models\Materia', 'materia_id');
    // }
}