<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    // LISTAR ÓRDENES (Admin)
    public function index()
    {
        return Orden::with(['cliente', 'productos', 'pago'])->latest()->get();
    }

    // CREAR UNA ORDEN NUEVA
    public function store(Request $request)
    {
        $request->validate([
            'cliente.nombre_completo' => 'required|string',
            'cliente.email' => 'required|email',
            'cliente.telefono' => 'nullable|string',
            'cliente.direccion' => 'nullable|string',
            'cliente.ciudad' => 'nullable|string',
            'cliente.pais' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'notas' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Buscar o crear cliente
            $cliente = Cliente::firstOrCreate(
                ['email' => $request->cliente['email']],
                $request->cliente
            );

            // Crear la orden
            $orden = Orden::create([
                'cliente_id' => $cliente->id,
                'estado' => 'pendiente',
                'total' => 0,
                'notas' => $request->notas,
            ]);

            $total = 0;

            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);
                $cantidad = $item['cantidad'];
                $precio = $producto->precio;
                $subtotal = $precio * $cantidad;

                $orden->productos()->attach($producto->id, [
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $orden->total = $total;
            $orden->save();

            DB::commit();

            return response()->json([
                'message' => 'Orden creada con éxito.',
                'orden_id' => $orden->id,
                'orden' => $orden->load('productos', 'cliente'),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al crear la orden.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    // DETALLE DE UNA ORDEN
    public function show($id)
    {
        $orden = Orden::with(['cliente', 'productos', 'pago'])->findOrFail($id);
        return response()->json($orden);
    }

    // CANCELAR UNA ORDEN MANUALMENTE
    public function cancelar($id, Request $request)
    {
        $orden = Orden::findOrFail($id);

        if ($orden->estado !== 'pendiente') {
            return response()->json(['error' => 'Solo se pueden cancelar órdenes pendientes.'], 400);
        }

        $orden->estado = 'cancelado';
        $orden->cancelado_en = now();
        $orden->motivo_cancelacion = $request->motivo ?? 'Cancelado por el cliente';
        $orden->save();

        return response()->json(['message' => 'Orden cancelada correctamente.']);
    }
}
