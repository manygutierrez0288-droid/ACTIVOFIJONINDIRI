<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('categorias', 'vida_util_anios')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->integer('vida_util_anios')->nullable()->after('porcentaje_valor_residual');
            });
        }

        $map = [
            // Indefinida (0)
            0 => [
                'Terrenos urbanos',
                'Terrenos rurales',
                'Áreas verdes públicas',
                'Estatuas y monumentos',
                'Cuadros institucionales'
            ],
            // 20 Años
            20 => [
                'Edificio municipal',
                'Mercado municipal',
                'Centro de salud municipal',
                'Escuelas municipales'
            ],
            // 30 Años
            30 => [
                'Puentes y alcantarillas',
                'Calles y pavimentación'
            ],
            // 5 Años
            5 => [
                'Escritorios',
                'Sillas',
                'Archivadores',
                'Mesas de reunión',
                'Estanterías',
                'Switches y routers',
                'Teléfonos IP',
                'Camionetas pickup',
                'Vehículos sedán',
                'Camiones de recolección',
                'Equipos de poda y jardinería',
                'Autoclaves',
                'Equipos de rayos X portátiles',
                'Extintores',
                'Radios de comunicación',
                'Relojes de pared',
                'Equipos de sonido para eventos'
            ],
            // 2 Años
            2 => [
                'Computadoras de escritorio',
                'Laptops',
                'Impresoras',
                'Servidores',
                'Proyectores'
            ],
            // 8 Años
            8 => [
                'Motocicletas',
                'Tractores viales',
                'Botes o lanchas'
            ],
            // 7 Años
            7 => [
                'Retroexcavadoras',
                'Compactadoras'
            ],
            // 10 Años
            10 => [
                'Bombas de agua',
                'Equipos de iluminación pública'
            ]
        ];

        foreach ($map as $anios => $nombres) {
            foreach ($nombres as $nombre) {
                // Check if exists by name
                $existing = DB::table('categorias')->where('nombre', $nombre)->first();

                if ($existing) {
                    // Update existing
                    DB::table('categorias')->where('id', $existing->id)->update([
                        'vida_util_anios' => $anios,
                        'updated_at' => now()
                    ]);
                } else {
                    // Create new with code
                    $code = strtoupper(substr($nombre, 0, 3)) . '-' . rand(1000, 9999);

                    // Ensure code uniqueness
                    while (DB::table('categorias')->where('codigo', $code)->exists()) {
                        $code = strtoupper(substr($nombre, 0, 3)) . '-' . rand(1000, 9999);
                    }

                    DB::table('categorias')->insert([
                        'nombre' => $nombre,
                        'vida_util_anios' => $anios,
                        'activo' => true,
                        'codigo' => $code,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categorias', 'vida_util_anios')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropColumn('vida_util_anios');
            });
        }
    }
};
