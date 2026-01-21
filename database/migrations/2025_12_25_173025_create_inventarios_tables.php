<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // e.g., "Inventario Anual 2025"
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');
            $table->foreignId('user_id')->constrained();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('inventario_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained()->onDelete('cascade');
            $table->foreignId('activo_fijo_id')->constrained();
            $table->boolean('verificado')->default(false);
            $table->timestamp('fecha_verificacion')->nullable();
            $table->foreignId('estado_fisico_id')->nullable()->constrained('estado_activos');
            $table->text('comentarios')->nullable();
            $table->foreignId('verificado_por')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_detalles');
        Schema::dropIfExists('inventarios');
    }
};
