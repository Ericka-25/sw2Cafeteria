<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    
    protected $fillable = ['fecha', 'total', 'idsucursal', 'idusuario', 'estado'];

    public $timestamps = false;
    
    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'idsucursal');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }

    public function detVentas()
    {
        return $this->hasMany(DetVentas::class, 'idventa');
    }
}
