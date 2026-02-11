<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();

        if ($locations->isEmpty()) {
            $this->call(LocationSeeder::class);
            $locations = Location::all();
        }

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $categoryIds = $categories->pluck('id', 'name');
        $faker = fake();

        $brands = [
            'Bosch',
            'Makita',
            'DeWalt',
            'Hilti',
            'Truper',
            'Stanley',
            'Metabo',
            'Milwaukee',
            'Karcher',
            'Honda',
            'Generac',
        ];

        $suppliers = [
            'Distribuciones Andina',
            'Ferreteria Central',
            'Suministros Obra Segura',
            'Herracenter',
            'Logistica Industrial Pacifico',
        ];

        $modelPrefixes = ['XR', 'MX', 'PRO', 'HD', 'XT', 'G', 'S'];

        $itemTemplates = [
            [
                'base_name' => 'Taladro percutor',
                'category' => 'Herramientas electricas',
                'unit' => 'UNIT',
                'weight' => [1.8, 3.8],
                'price' => [250, 900],
                'consumable' => false,
            ],
            [
                'base_name' => 'Esmeril angular 4.5"',
                'category' => 'Herramientas electricas',
                'unit' => 'UNIT',
                'weight' => [1.6, 3.2],
                'price' => [180, 650],
                'consumable' => false,
            ],
            [
                'base_name' => 'Sierra circular',
                'category' => 'Herramientas electricas',
                'unit' => 'UNIT',
                'weight' => [3.0, 5.5],
                'price' => [300, 1200],
                'consumable' => false,
            ],
            [
                'base_name' => 'Juego de llaves combinadas',
                'category' => 'Herramientas manuales',
                'unit' => 'BOX',
                'weight' => [1.0, 2.5],
                'price' => [80, 250],
                'consumable' => false,
            ],
            [
                'base_name' => 'Martillo carpintero',
                'category' => 'Herramientas manuales',
                'unit' => 'UNIT',
                'weight' => [0.6, 1.2],
                'price' => [35, 120],
                'consumable' => false,
            ],
            [
                'base_name' => 'Nivel laser',
                'category' => 'Equipos de medicion',
                'unit' => 'UNIT',
                'weight' => [0.8, 1.5],
                'price' => [250, 950],
                'consumable' => false,
            ],
            [
                'base_name' => 'Cinta metrica 8 m',
                'category' => 'Equipos de medicion',
                'unit' => 'UNIT',
                'weight' => [0.2, 0.6],
                'price' => [15, 40],
                'consumable' => false,
            ],
            [
                'base_name' => 'Casco de seguridad',
                'category' => 'Seguridad industrial',
                'unit' => 'UNIT',
                'weight' => [0.3, 0.6],
                'price' => [18, 45],
                'consumable' => true,
            ],
            [
                'base_name' => 'Arnes de seguridad',
                'category' => 'Seguridad industrial',
                'unit' => 'UNIT',
                'weight' => [1.2, 2.6],
                'price' => [90, 240],
                'consumable' => false,
            ],
            [
                'base_name' => 'Andamio tubular',
                'category' => 'Andamios y escaleras',
                'unit' => 'UNIT',
                'weight' => [35, 65],
                'price' => [400, 1200],
                'consumable' => false,
            ],
            [
                'base_name' => 'Escalera telescopica',
                'category' => 'Andamios y escaleras',
                'unit' => 'UNIT',
                'weight' => [8, 16],
                'price' => [180, 420],
                'consumable' => false,
            ],
            [
                'base_name' => 'Generador electrico 3.5 kW',
                'category' => 'Equipos de energia y generacion',
                'unit' => 'UNIT',
                'weight' => [35, 55],
                'price' => [900, 2500],
                'consumable' => false,
            ],
            [
                'base_name' => 'Extension industrial 20 m',
                'category' => 'Equipos de energia y generacion',
                'unit' => 'UNIT',
                'weight' => [2, 5],
                'price' => [45, 120],
                'consumable' => true,
            ],
            [
                'base_name' => 'Soldadora inverter',
                'category' => 'Equipos de soldadura y corte',
                'unit' => 'UNIT',
                'weight' => [6, 12],
                'price' => [350, 1200],
                'consumable' => false,
            ],
            [
                'base_name' => 'Disco de corte 9"',
                'category' => 'Equipos de soldadura y corte',
                'unit' => 'UNIT',
                'weight' => [0.2, 0.6],
                'price' => [8, 22],
                'consumable' => true,
            ],
            [
                'base_name' => 'Mezcladora de concreto',
                'category' => 'Maquinaria ligera',
                'unit' => 'UNIT',
                'weight' => [120, 220],
                'price' => [1200, 3200],
                'consumable' => false,
            ],
            [
                'base_name' => 'Compactadora de placa',
                'category' => 'Maquinaria ligera',
                'unit' => 'UNIT',
                'weight' => [80, 140],
                'price' => [1400, 3800],
                'consumable' => false,
            ],
            [
                'base_name' => 'Cemento gris 50 kg',
                'category' => 'Materiales y consumibles',
                'unit' => 'KG',
                'weight' => [25, 50],
                'price' => [6, 12],
                'consumable' => true,
            ],
            [
                'base_name' => 'Pintura de senalizacion 1 gal',
                'category' => 'Materiales y consumibles',
                'unit' => 'LT',
                'weight' => [3.5, 5.0],
                'price' => [25, 55],
                'consumable' => true,
            ],
            [
                'base_name' => 'Lampara LED de obra',
                'category' => 'Iluminacion y senalizacion',
                'unit' => 'UNIT',
                'weight' => [1.5, 4.0],
                'price' => [45, 160],
                'consumable' => false,
            ],
            [
                'base_name' => 'Cono de seguridad 70 cm',
                'category' => 'Iluminacion y senalizacion',
                'unit' => 'UNIT',
                'weight' => [0.8, 1.6],
                'price' => [8, 18],
                'consumable' => true,
            ],
        ];

        $totalItems = 30;

        for ($i = 0; $i < $totalItems; $i++) {
            $template = $faker->randomElement($itemTemplates);
            $brand = $faker->randomElement($brands);
            $model = $faker->randomElement($modelPrefixes) . '-' . $faker->numberBetween(100, 9999);
            $purchaseDate = Carbon::instance($faker->dateTimeBetween('-8 years', '-3 months'));
            $purchasePrice = $faker->randomFloat(2, $template['price'][0], $template['price'][1]);
            $currentValue = round($purchasePrice * $faker->randomFloat(2, 0.4, 0.95), 2);
            $status = $faker->randomElement([
                'AVAILABLE',
                'AVAILABLE',
                'IN_USE',
                'IN_USE',
                'IN_REPAIR',
                'DAMAGED',
            ]);

            $condition = match ($status) {
                'IN_REPAIR', 'DAMAGED' => $faker->randomElement(['FAIR', 'POOR']),
                default => $faker->randomElement(['EXCELLENT', 'GOOD', 'FAIR']),
            };

            $categoryId = $categoryIds[$template['category']] ?? $categories->first()?->id;
            $weightKg = $faker->randomFloat(2, $template['weight'][0], $template['weight'][1]);

            Item::create([
                'code' => $faker->unique()->bothify('ITM-####-??'),
                'name' => $template['base_name'] . ' ' . $brand . ' ' . $model,
                'description' => 'Equipo de obra: ' . $template['base_name'] . '.',
                'category_id' => $categoryId,
                'current_location_id' => $locations->random()->id,
                'status' => $status,
                'condition' => $condition,
                'purchase_date' => $purchaseDate->format('Y-m-d'),
                'purchase_price' => $purchasePrice,
                'current_value' => $currentValue,
                'serial_number' => $faker->unique()->bothify('SN-########'),
                'brand' => $brand,
                'model' => $model,
                'supplier' => $faker->randomElement($suppliers),
                'warranty_expiry' => $purchaseDate->copy()->addYears($faker->numberBetween(1, 3))->format('Y-m-d'),
                'barcode' => $faker->ean13(),
                'qr_code' => null,
                'minimum_stock' => $template['consumable']
                    ? $faker->numberBetween(10, 50)
                    : $faker->numberBetween(0, 5),
                'unit_of_measure' => $template['unit'],
                'weight_kg' => $weightKg,
                'dimensions' => null,
            ]);
        }

        $this->command->info('Seeded items: ' . $totalItems);
    }
}
