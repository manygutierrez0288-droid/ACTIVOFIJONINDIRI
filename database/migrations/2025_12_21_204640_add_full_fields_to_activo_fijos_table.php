<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activo_fijos', function (Blueprint $table) {
            // Drop old estado column if it exists
            if (Schema::hasColumn('activo_fijos', 'estado')) {
                $table->dropColumn('estado');
            }

            // Add new foreign keys
            $table->foreignId('marca_id')->nullable()->after('nombre')->constrained('marcas')->nullOnDelete();
            $table->foreignId('modelo_id')->nullable()->after('marca_id')->constrained('modelos')->nullOnDelete();
            $table->foreignId('color_id')->nullable()->after('modelo_id')->constrained('colors')->nullOnDelete();
            $table->foreignId('fuente_id')->nullable()->after('ubicacion_id')->constrained('fuentes')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->after('fuente_id')->constrained('proveedors')->nullOnDelete();
            $table->foreignId('responsable_id')->nullable()->after('proveedor_id')->constrained('personal_responsables')->nullOnDelete();
            $table->foreignId('estado_id')->nullable()->after('responsable_id')->constrained('estado_activos')->nullOnDelete();

            // Audit fields
            $table->foreignId('user_creacion_id')->nullable()->after('depreciacion_acumulada')->constrained('users')->nullOnDelete();
            $table->foreignId('user_modificacion_id')->nullable()->after('user_creacion_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activo_fijos', function (Blueprint $table) {
            $table->dropForeign(['marca_id']);
            $table->dropForeign(['modelo_id']);
            $table->dropForeign(['color_id']);
            $table->dropForeign(['fuente_id']);
            $table->dropForeign(['proveedor_id']);
            $table->dropForeign(['responsable_id']);
            $table->dropForeign(['estado_id']);
            $table->dropForeign(['user_creacion_id']);
            $table->dropForeign(['user_modificacion_id']);

            $table->dropColumn(['marca_id', 'modelo_id', 'color_id', 'fuente_id', 'proveedor_id', 'responsable_id', 'estado_id', 'user_creacion_id', 'user_modificacion_id']);

            $table->string('estado')->default('activo');
        });
    }
};
