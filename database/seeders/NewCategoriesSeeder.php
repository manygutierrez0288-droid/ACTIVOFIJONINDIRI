<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['codigo' => '1000-1-01', 'nombre' => 'Terrenos urbanos', 'grupo_categoria' => 'Terrenos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1', 'vida_util_anios' => 0],
            ['codigo' => '1000-1-02', 'nombre' => 'Terrenos rurales', 'grupo_categoria' => 'Terrenos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2', 'vida_util_anios' => 0],
            ['codigo' => '1000-1-03', 'nombre' => 'Áreas verdes públicas', 'grupo_categoria' => 'Terrenos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3', 'vida_util_anios' => 0],

            ['codigo' => '1100-1-01', 'nombre' => 'Edificio municipal', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1100-1-02', 'nombre' => 'Mercado municipal', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1100-1-03', 'nombre' => 'Centro de salud municipal', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1100-1-04', 'nombre' => 'Escuelas municipales', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],
            ['codigo' => '1100-1-05', 'nombre' => 'Puentes y alcantarillas', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '5'],
            ['codigo' => '1100-1-06', 'nombre' => 'Calles y pavimentación', 'grupo_categoria' => 'Edificaciones e Infraestructura', 'subcategoria' => 'Bienes Tangibles', 'clase' => '6'],

            ['codigo' => '1200-1-01', 'nombre' => 'Escritorios', 'grupo_categoria' => 'Mobiliario y Equipos de Oficina', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1200-1-02', 'nombre' => 'Sillas', 'grupo_categoria' => 'Mobiliario y Equipos de Oficina', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1200-1-03', 'nombre' => 'Archivadores', 'grupo_categoria' => 'Mobiliario y Equipos de Oficina', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1200-1-04', 'nombre' => 'Mesas de reunión', 'grupo_categoria' => 'Mobiliario y Equipos de Oficina', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],
            ['codigo' => '1200-1-05', 'nombre' => 'Estanterías', 'grupo_categoria' => 'Mobiliario y Equipos de Oficina', 'subcategoria' => 'Bienes Tangibles', 'clase' => '5'],

            ['codigo' => '1300-1-01', 'nombre' => 'Computadoras de escritorio', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1300-1-02', 'nombre' => 'Laptops', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1300-1-03', 'nombre' => 'Impresoras', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1300-1-04', 'nombre' => 'Servidores', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],
            ['codigo' => '1300-1-05', 'nombre' => 'Proyectores', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '5'],
            ['codigo' => '1300-1-06', 'nombre' => 'Switches y routers', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '6'],
            ['codigo' => '1300-1-07', 'nombre' => 'Teléfonos IP', 'grupo_categoria' => 'Equipos de Tecnología', 'subcategoria' => 'Bienes Tangibles', 'clase' => '7'],

            ['codigo' => '1400-1-01', 'nombre' => 'Camionetas pickup', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1400-1-02', 'nombre' => 'Vehículos sedán', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1400-1-03', 'nombre' => 'Motocicletas', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1400-1-04', 'nombre' => 'Camiones de recolección', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],
            ['codigo' => '1400-1-05', 'nombre' => 'Tractores viales', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '5'],
            ['codigo' => '1400-1-06', 'nombre' => 'Botes o lanchas', 'grupo_categoria' => 'Vehículos y Transporte', 'subcategoria' => 'Bienes Tangibles', 'clase' => '6'],

            ['codigo' => '1500-1-01', 'nombre' => 'Retroexcavadoras', 'grupo_categoria' => 'Maquinaria y Equipos Técnicos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1500-1-02', 'nombre' => 'Compactadoras', 'grupo_categoria' => 'Maquinaria y Equipos Técnicos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1500-1-03', 'nombre' => 'Bombas de agua', 'grupo_categoria' => 'Maquinaria y Equipos Técnicos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1500-1-04', 'nombre' => 'Equipos de iluminación pública', 'grupo_categoria' => 'Maquinaria y Equipos Técnicos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],
            ['codigo' => '1500-1-05', 'nombre' => 'Equipos de poda y jardinería', 'grupo_categoria' => 'Maquinaria y Equipos Técnicos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '5'],

            ['codigo' => '1600-1-01', 'nombre' => 'Camas hospitalarias', 'grupo_categoria' => 'Equipos Médicos y de Salud', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1600-1-02', 'nombre' => 'Autoclaves', 'grupo_categoria' => 'Equipos Médicos y de Salud', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1600-1-03', 'nombre' => 'Equipos de rayos X portátiles', 'grupo_categoria' => 'Equipos Médicos y de Salud', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1600-1-04', 'nombre' => 'Refrigeradores de vacunas', 'grupo_categoria' => 'Equipos Médicos y de Salud', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],

            ['codigo' => '1700-1-01', 'nombre' => 'Chalecos reflectivos', 'grupo_categoria' => 'Equipos de Seguridad y Emergencia', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1700-1-02', 'nombre' => 'Extintores', 'grupo_categoria' => 'Equipos de Seguridad y Emergencia', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1700-1-03', 'nombre' => 'Radios de comunicación', 'grupo_categoria' => 'Equipos de Seguridad y Emergencia', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
            ['codigo' => '1700-1-04', 'nombre' => 'Cascos y uniformes', 'grupo_categoria' => 'Equipos de Seguridad y Emergencia', 'subcategoria' => 'Bienes Tangibles', 'clase' => '4'],

            ['codigo' => '1800-1-01', 'nombre' => 'Estatuas y monumentos', 'grupo_categoria' => 'Obras de Arte y Bienes Culturales', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1800-1-02', 'nombre' => 'Cuadros institucionales', 'grupo_categoria' => 'Obras de Arte y Bienes Culturales', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],

            ['codigo' => '1900-01-01', 'nombre' => 'Banderas nacionales', 'grupo_categoria' => 'Otros Activos Fijos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '1'],
            ['codigo' => '1900-01-02', 'nombre' => 'Relojes de pared institucionales', 'grupo_categoria' => 'Otros Activos Fijos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '2'],
            ['codigo' => '1900-01-03', 'nombre' => 'Equipos de sonido para eventos', 'grupo_categoria' => 'Otros Activos Fijos', 'subcategoria' => 'Bienes Tangibles', 'clase' => '3'],
        ];

        foreach ($categorias as $cat) {
            \App\Models\Categoria::updateOrCreate(
                ['codigo' => $cat['codigo']],
                [
                    'nombre' => $cat['nombre'],
                    'grupo_categoria' => $cat['grupo_categoria'],
                    'subcategoria' => $cat['subcategoria'],
                    'clase' => $cat['clase'],
                    'vida_util_anios' => $cat['vida_util_anios'] ?? 5,
                    'activo' => true
                ]
            );
        }
    }
}
