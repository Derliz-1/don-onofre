<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_id',
        'referencia_pago',
        'metodo',
        'estado',
        'confirmado_en',
        'cancelado_en',
        'tipo_respuesta',
        'notas_cancelacion',
        'respuesta_adamspay',
    ];

    protected $casts = [
        'respuesta_adamspay' => 'array',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
