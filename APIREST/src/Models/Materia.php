<?php
namespace App\Models;

class Materia extends \Illuminate\Database\Eloquent\Model {
    public $timestamps = false;
    // protected $fillable = ['email', 'tipo_id', 'clave', 'nombre', 'legajo'];

    public function profesor()
    {
        return $this->belongsTo('App\Models\User', 'profesor_id');
    }

    public function alumnos()
    {
        return $this->hasMany('App\Models\Inscripto', 'materia_id');
    }
}