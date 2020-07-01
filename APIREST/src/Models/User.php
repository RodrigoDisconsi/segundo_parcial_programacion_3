<?php
namespace App\Models;

class User extends \Illuminate\Database\Eloquent\Model {
    public $timestamps = false;
    protected $fillable = ['email', 'tipo_id', 'clave', 'nombre', 'legajo'];

    public function tipo()
    {
        return $this->belongsTo('App\Models\Tipo');
    }
}