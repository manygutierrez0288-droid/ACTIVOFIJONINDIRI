<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_responsables', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('restrict');
            $table->string('numero_cedula', 20)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_responsables');
    }
};
