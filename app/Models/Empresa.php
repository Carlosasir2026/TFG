<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nombre',
        'cif',
        'direccion',
        'provincia',
        'pais',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_empresa');
    }

    public function almacenes()
    {
        return $this->hasMany(Almacen::class, 'id_empresa');
    }
}