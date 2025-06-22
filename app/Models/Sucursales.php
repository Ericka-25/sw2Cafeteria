<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    use HasFactory;    

    protected $fillable = ['direccion','zona','celular', 'estado'];

    public $timestamps = false;
    
    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'idsucursal');
    }

    public function sucursalesproductos()
    {
        return $this->hasMany(SucursalesProductos::class, 'idsucursal');
    }

    public function compras()
    {
        return $this->hasMany(Compras::class, 'idsucursal');
    }
}
