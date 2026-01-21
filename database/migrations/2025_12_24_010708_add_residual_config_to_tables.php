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
        Schema::table('categorias', function (Blueprint $table) {
            $table->decimal('porcentaje_valor_residual', 5, 2)->default(10.00)->after('nombre'); // Default 10%
        });

        Schema::table('activo_fijos', function (Blueprint $table) {
            $table->boolean('valor_residual_automatico')->default(true)->after('valor_residual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('porcentaje_valor_residual');
        });

        Schema::table('activo_fijos', function (Blueprint $table) {
            $table->dropColumn('valor_residual_automatico');
        });
    }
};
