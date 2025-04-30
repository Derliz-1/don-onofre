<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\API\ProductoController;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\API\OrdenController;
use App\Http\Controllers\API\PagoController;
use App\Http\Controllers\API\AdminController;

// LOGIN de Administrador
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !$user->is_admin) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    return response()->json([
        'token' => $user->createToken('admin-token')->plainTextToken,
        'user' => $user
    ]);
});

Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'resumen']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::post('/productos/upload-imagen', [ProductoController::class, 'uploadImagen']);
    Route::put('/productos/{producto}', [ProductoController::class, 'update']);
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);
});

Route::apiResource('productos', ProductoController::class)->only(['index', 'show']);
Route::apiResource('clientes', ClienteController::class)->only(['store']);
Route::apiResource('ordenes', OrdenController::class)->only(['index', 'store', 'show']);
Route::post('ordenes/{id}/cancelar', [OrdenController::class, 'cancelar']);

Route::get('/pagos', [PagoController::class, 'index']);
Route::get('pagos/{orden_id}', [PagoController::class, 'show']);
Route::post('pagos/{orden_id}/generar', [PagoController::class, 'generarLinkPago']);
Route::post('pagos/{pago_id}/cancelar', [PagoController::class, 'cancelarPago']);
Route::post('pagos/webhook', [PagoController::class, 'webhook']);
Route::post('pagos/{referencia}/confirmar-simulado', [PagoController::class, 'confirmarPagoSimulado']);;

Route::get('/crear-admin', function () {
    $admin = User::updateOrCreate(
        ['email' => 'admin@dononofre.com'],
        [
            'name' => 'Administrador',
            'password' => Hash::make('admin123'), // Contraseña: admin123
            'is_admin' => true
        ]
    );

    return response()->json(['message' => 'Usuario administrador creado', 'admin' => $admin]);
});
