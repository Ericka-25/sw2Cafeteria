<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalesProductos extends Model
{
    use HasFactory;
    
    protected $table = 'sucursalesproductos';

    protected $fillable = ['idsucursal', 'idproducto', 'stock'];

    // Indicar que no hay una clave primaria incrementada
    public $incrementing = false;

    // Si no hay una columna 'id', indicar que no hay clave primaria
    protected $primaryKey = null;
    
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idproducto');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'idsucursal');
    }
}
