<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PagoController extends Controller
{
    public function generarLinkPago($orden_id)
    {
        $orden = Orden::with('cliente')->findOrFail($orden_id);
        $referencia = strtoupper(Str::random(10));
        $linkPago = "https://adamspay.com/pagar/" . $referencia;

        $pago = Pago::create([
            'orden_id' => $orden->id,
            'referencia_pago' => $referencia,
            'estado' => 'pendiente',
            'respuesta_adamspay' => null,
        ]);

        return response()->json([
            'message' => 'Link de pago generado correctamente.',
            'pago' => $pago,
            'link_pago' => $linkPago
        ], 201);
    }

        public function index(Request $request)
    {
        $query = Pago::with('orden.cliente');

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha') && $request->fecha !== '') {
            $query->whereDate('created_at', $request->fecha);
        }

        return $query->latest()->get();
    }


    public function show($orden_id)
    {
        $orden = Orden::with('pago')->findOrFail($orden_id);

        return response()->json([
            'orden_id' => $orden->id,
            'estado_pago' => $orden->pago->estado ?? 'pendiente',
            'referencia_pago' => $orden->pago->referencia_pago ?? null,
            'pago' => $orden->pago
        ]);
    }

    public function webhook(Request $request)
    {
        $request->validate([
            'referencia_pago' => 'required|string|exists:pagos,referencia_pago',
            'estado' => 'required|in:exitoso,fallido,cancelado,expirado',
        ]);
    
        $pago = Pago::where('referencia_pago', $request->referencia_pago)->firstOrFail();
    
        $pago->estado = $request->estado;
        $pago->tipo_respuesta = 'webhook';
        $pago->respuesta_adamspay = json_encode($request->all());
    
        if ($request->estado === 'exitoso') {
            $pago->confirmado_en = now();
    
            $orden = $pago->orden;
            $orden->estado = 'pagado';
            $orden->pagado_en = now();
            $orden->save();
    
            foreach ($orden->productos as $producto) {
                $producto->decrement('stock', $producto->pivot->cantidad);
            }
        }
    
        if ($request->estado === 'cancelado') {
            $pago->cancelado_en = now();
        }
    
        $pago->save();
    
        return response()->json([
            'message' => 'Pago actualizado correctamente desde webhook.',
            'estado' => $pago->estado
        ]);
    }    

    public function cancelarPago($pago_id, Request $request)
    {
        $pago = Pago::findOrFail($pago_id);

        if ($pago->estado !== 'pendiente') {
            return response()->json([
                'error' => 'El pago ya fue procesado y no puede cancelarse.'
            ], 400);
        }

        $pago->estado = 'cancelado';
        $pago->cancelado_en = now();
        $pago->tipo_respuesta = 'manual';
        $pago->notas_cancelacion = $request->input('motivo') ?? 'Cancelado manualmente';
        $pago->save();

        $pago->orden->estado = 'cancelado';
        $pago->orden->cancelado_en = now();
        $pago->orden->motivo_cancelacion = $pago->notas_cancelacion;
        $pago->orden->save();

        return response()->json([
            'message' => 'Pago cancelado correctamente.',
            'pago' => $pago
        ]);
    }

    public function confirmarPagoSimulado($referencia)
    {
        $pago = Pago::where('referencia_pago', $referencia)->firstOrFail();
    
        if ($pago->estado !== 'pendiente') {
            return response()->json(['error' => 'El pago ya fue procesado.'], 400);
        }
    
        $pago->estado = 'exitoso';
        $pago->tipo_respuesta = 'manual';
        $pago->confirmado_en = now();
        $pago->respuesta_adamspay = json_encode(['simulado' => true]);
        $pago->save();
    
        $orden = $pago->orden;
        $orden->estado = 'pagado';
        $orden->pagado_en = now();
        $orden->save();
    
        foreach ($orden->productos as $producto) {
            $producto->decrement('stock', $producto->pivot->cantidad);
        }
    
        return response()->json([
            'message' => 'Pago simulado como exitoso y stock actualizado.',
            'estado' => $pago->estado
        ]);
    } 
}   