<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Herramientas electricas',
                'description' => 'Herramientas con motor electrico para perforar, cortar y pulir.',
                'color' => '#1D4ED8',
            ],
            [
                'name' => 'Herramientas manuales',
                'description' => 'Herramientas basicas para armado, ajuste y mantenimiento diario.',
                'color' => '#0F766E',
            ],
            [
                'name' => 'Equipos de medicion',
                'description' => 'Equipos para medir distancias, niveles y alineaciones.',
                'color' => '#7C3AED',
            ],
            [
                'name' => 'Seguridad industrial',
                'description' => 'Elementos de proteccion personal para obra y bodega.',
                'color' => '#DC2626',
            ],
            [
                'name' => 'Andamios y escaleras',
                'description' => 'Estructuras temporales para trabajo en altura.',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Equipos de energia y generacion',
                'description' => 'Generadores, cables y equipos de respaldo electrico.',
                'color' => '#0284C7',
            ],
            [
                'name' => 'Equipos de soldadura y corte',
                'description' => 'Soldadoras, esmeriles y consumibles de corte.',
                'color' => '#9A3412',
            ],
            [
                'name' => 'Maquinaria ligera',
                'description' => 'Equipos moviles para mezcla, compactacion y limpieza.',
                'color' => '#6B7280',
            ],
            [
                'name' => 'Materiales y consumibles',
                'description' => 'Materiales de obra y consumibles de alta rotacion.',
                'color' => '#16A34A',
            ],
            [
                'name' => 'Iluminacion y senalizacion',
                'description' => 'Luces de obra, senales y elementos de advertencia.',
                'color' => '#0E7490',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'color' => $category['color'],
                    'icon' => null,
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
