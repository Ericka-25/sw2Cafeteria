<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'precio_compra','precio_venta','rop','idproveedor', 'estado'];

    public $timestamps = false;
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'idproveedor');
    }

    public function sucursalesProductos()
    {
        return $this->hasMany(SucursalesProductos::class, 'idtela');
    }    
}
