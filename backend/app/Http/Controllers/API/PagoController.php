<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PagoController extends Controller
{
    // 1. GENERAR LINK DE PAGO REAL CON ADAMSPAY
    public function generarLinkPago($orden_id)
    {
        $orden = Orden::with('cliente', 'productos')->findOrFail($orden_id);

        $response = Http::post('https://api.adamspay.com/sandbox/api/deudas', [
            'monto' => $orden->total,
            'descripcion' => 'Compra de productos Don Onofre',
            'id_cliente' => $orden->cliente->id,
            'nombre_cliente' => $orden->cliente->nombre,
            'email_cliente' => $orden->cliente->email,
            'webhook' => route('pago.webhook'),
            'retornar_url' => 'https://don-onofre-three.vercel.app/confirmacion/' . $orden->id,
            'apikey' => env('ADAMSPAY_API_KEY'),
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Error al generar el link de pago.'], 500);
        }

        $data = $response->json();

        $pago = Pago::create([
            'orden_id' => $orden->id,
            'referencia_pago' => $data['referencia_pago'],
            'estado' => 'pendiente',
            'respuesta_adamspay' => json_encode($data),
        ]);

        return response()->json([
            'message' => 'Link de pago generado correctamente.',
            'pago' => $pago,
            'link_pago' => $data['payment_url']
        ]);
    }

    // 2. WEBHOOK OFICIAL DE ADAMSPAY
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

    // 3. LISTADO DE PAGOS (ADMIN)
    public function index(Request $request)
    {
        $query = Pago::with('orden.cliente');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        return $query->latest()->get();
    }

    // 4. VER DETALLE DE PAGO
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

    // 5. CANCELAR MANUALMENTE UN PAGO
    public function cancelarPago($pago_id, Request $request)
    {
        $pago = Pago::findOrFail($pago_id);

        if ($pago->estado !== 'pendiente') {
            return response()->json(['error' => 'El pago ya fue procesado.'], 400);
        }

        $pago->estado = 'cancelado';
        $pago->cancelado_en = now();
        $pago->tipo_respuesta = 'manual';
        $pago->notas_cancelacion = $request->input('motivo') ?? 'Cancelado manualmente';
        $pago->save();

        $orden = $pago->orden;
        $orden->estado = 'cancelado';
        $orden->cancelado_en = now();
        $orden->motivo_cancelacion = $pago->notas_cancelacion;
        $orden->save();

        return response()->json([
            'message' => 'Pago cancelado correctamente.',
            'pago' => $pago
        ]);
    }
}