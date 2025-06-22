<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGastos extends Model
{
    use HasFactory;

    protected $table = 'tipogastos';

    protected $fillable = ['descripcion'];

    public $timestamps = false;

    public function adiciongastos()
    {
        return $this->hasMany(AdicionGastos::class, 'idgasto');
    }
}
