<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'total', 'idproveedor','idsucursal','estado'];

    public $timestamps = false;

    public function detCompras()
    {
        return $this->hasMany(DetCompras::class, 'idcompra');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'idproveedor');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'idsucursal');
    }

    public function recepcion()
    {
        //return $this->hasMany(Recepciones::class, 'idcompra');
        return $this->hasOne(Recepciones::class, 'idcompra');
    }

    public function isRecepcionado()
    {
        return $this->recepcion()->exists();
    }
}
