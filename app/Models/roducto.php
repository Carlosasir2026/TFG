<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_almacen',
        'nombre',
        'cantidad',
        'precio',
        'stock_minimo',
        'codigo_barras',
    ];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen');
    }
}