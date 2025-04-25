<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function resumen()
    {
        $ordenes = Orden::with('cliente')->latest()->get();
    
        return response()->json([
            'total_ordenes' => $ordenes->count(),
            'pagadas' => $ordenes->where('estado', 'pagado')->count(),
            'pendientes' => $ordenes->where('estado', 'pendiente')->count(),
            'canceladas' => $ordenes->where('estado', 'cancelado')->count(),
            'recaudado' => $ordenes->where('estado', 'pagado')->sum('total'),
            'ultimos_pedidos' => $ordenes->take(5), 
            'todas' => $ordenes, 
        ]);
    }    
}