<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'institution_name',
                'value' => 'Sistema de Activos Fijos',
                'label' => 'Nombre de la Institución',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'institution_logo',
                'value' => null,
                'label' => 'Logo de la Institución',
                'type' => 'image',
                'group' => 'general',
            ],

            // Reports
            [
                'key' => 'report_header',
                'value' => 'Reporte Oficial',
                'label' => 'Encabezado de Reportes',
                'type' => 'text',
                'group' => 'reports',
            ],
            [
                'key' => 'report_footer',
                'value' => 'Generado por SIAFNIN',
                'label' => 'Pie de Página de Reportes',
                'type' => 'text',
                'group' => 'reports',
            ],
            [
                'key' => 'items_per_page',
                'value' => '10',
                'label' => 'Items por Página (Reportes)',
                'type' => 'number',
                'group' => 'reports',
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
