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
            $table->string('codigo', 20)->change();
            $table->string('grupo_categoria')->nullable()->after('nombre');
            $table->string('subcategoria')->nullable()->after('grupo_categoria');
            $table->string('clase')->nullable()->after('subcategoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('codigo', 5)->change();
            $table->dropColumn(['grupo_categoria', 'subcategoria', 'clase']);
        });
    }
};
