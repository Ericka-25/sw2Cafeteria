<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'estado'];

    public $timestamps = false;
    
    public function telas()
    {
        return $this->hasMany(Telas::class, 'idproveedor');
    }
}
