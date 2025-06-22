<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepciones extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'tiempo', 'idcompra', 'idsucursal', 'idusuario', 'estado'];

    public $timestamps = false;

    // Indicar que no hay una clave primaria incrementada
    public $incrementing = false;

    // Si no hay una columna 'id', indicar que no hay clave primaria
    protected $primaryKey = null;


    public function compra()
    {
        return $this->belongsTo(Compras::class, 'idcompra');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'idsucursal');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }
}
