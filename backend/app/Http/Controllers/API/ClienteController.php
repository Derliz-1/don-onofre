<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string',
            'email' => 'required|email|unique:clientes',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'pais' => 'nullable|string',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json([
            'message' => 'Cliente registrado correctamente.',
            'cliente' => $cliente
        ], 201);
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return response()->json([
            'message' => 'Cliente actualizado correctamente.',
            'cliente' => $cliente
        ]);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado.']);
    }
}

