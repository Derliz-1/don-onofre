<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained()->onDelete('cascade');
            $table->string('referencia_pago')->unique();
            $table->string('metodo')->default('AdamsPay');
            $table->enum('estado', ['pendiente', 'exitoso', 'fallido', 'cancelado', 'expirado'])->default('pendiente');
            $table->timestamp('confirmado_en')->nullable();
            $table->timestamp('cancelado_en')->nullable();
            $table->string('tipo_respuesta')->nullable(); // webhook o manual
            $table->text('notas_cancelacion')->nullable();
            $table->json('respuesta_adamspay')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

