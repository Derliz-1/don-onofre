<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ProductoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('admin') === 'true') {
            return response()->json([
                'productos' => Producto::orderByDesc('created_at')->get()
            ]);
        } else {
            $perPage = $request->get('per_page', 6);

            $productos = Producto::where('activo', true)
                ->where('disponible', true)
                ->where('agotado', false)
                ->paginate($perPage);

            return response()->json($productos);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen_url' => 'nullable|url',
            'disponible' => 'boolean',
            'activo' => 'boolean',
            'agotado' => 'boolean',
            'fecha_eliminacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $producto = Producto::create($validator->validated());

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'producto' => $producto
        ], 201);
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'imagen_url' => 'nullable|url',
            'disponible' => 'boolean',
            'activo' => 'boolean',
            'agotado' => 'boolean',
            'fecha_eliminacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $producto->update($validator->validated());

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'producto' => $producto
        ]);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        $producto->update([
            'activo' => false,
            'disponible' => false,
            'fecha_eliminacion' => now()
        ]);

        return response()->json([
            'message' => 'Producto marcado como eliminado (soft delete).',
            'producto' => $producto
        ]);
    }
    
    use Illuminate\Support\Facades\Storage;

    public function uploadImagen(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
    
        try {
            $request->validate([
                'imagen' => 'required|image|max:2048',
            ]);
    
            $ruta = $request->file('imagen')->store('public/productos');
    
            $url = url(Storage::url($ruta)); // Genera URL pública
    
            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al subir imagen', 'mensaje' => $e->getMessage()], 500);
        }
    }    
}    

    