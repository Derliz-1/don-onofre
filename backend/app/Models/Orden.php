<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orden extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'estado',
        'total',
        'notas',
        'pagado_en',
        'cancelado_en',
        'motivo_cancelacion',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'orden_producto')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
