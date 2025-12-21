<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activo_fijos')->onDelete('cascade');
            $table->foreignId('ubicacion_origen_id')->constrained('ubicacions')->onDelete('restrict');
            $table->foreignId('ubicacion_destino_id')->constrained('ubicacions')->onDelete('restrict');
            $table->foreignId('responsable_origen_id')->constrained('personal_responsables')->onDelete('restrict');
            $table->foreignId('responsable_destino_id')->constrained('personal_responsables')->onDelete('restrict');
            $table->dateTime('fecha')->useCurrent();
            $table->text('motivo');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
