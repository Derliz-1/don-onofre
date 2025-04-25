<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado', 'expirado', 'fallido'])->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->text('notas')->nullable();
            $table->timestamp('pagado_en')->nullable();
            $table->timestamp('cancelado_en')->nullable();
            $table->text('motivo_cancelacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};

