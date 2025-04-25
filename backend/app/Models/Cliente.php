<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'pais',
    ];

    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }
}
