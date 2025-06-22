<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetVentas extends Model
{
    use HasFactory;
    
    protected $fillable = ['idventa', 'idproducto', 'cantidad', 'total', 'estado'];

    // Indicar que no hay una clave primaria incrementada
    public $incrementing = false;

    // Si no hay una columna 'id', indicar que no hay clave primaria
    protected $primaryKey = null;
    
    public $timestamps = false;

    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'idventa');
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idproducto');
    }
}
