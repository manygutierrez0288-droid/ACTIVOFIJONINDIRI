<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activo_fijos')->onDelete('cascade');
            $table->date('fecha');
            $table->text('descripcion');
            $table->decimal('costo', 12, 2)->nullable();
            $table->foreignId('tecnico_id')->nullable()->constrained('tecnicos')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->nullOnDelete();
            $table->foreignId('estado_id')->constrained('estado_activos')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
