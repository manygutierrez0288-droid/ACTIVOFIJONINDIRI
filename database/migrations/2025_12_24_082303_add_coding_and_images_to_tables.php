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
            $table->string('codigo', 5)->nullable()->after('nombre');
        });

        Schema::table('activo_fijos', function (Blueprint $table) {
            $table->string('codigo_inventario', 20)->nullable()->unique()->after('id');
            $table->string('imagen_url')->nullable()->after('vida_util_anios');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });

        Schema::table('activo_fijos', function (Blueprint $table) {
            $table->dropColumn(['codigo_inventario', 'imagen_url']);
        });
    }
};
