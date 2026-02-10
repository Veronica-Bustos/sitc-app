<?php

namespace Database\Seeders;

use App\Enums\LocationStatusEnum;
use App\Enums\LocationTypeEnum;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'code' => 'LOC-BVA',
                'name' => 'Bodegas Villa Alsacia',
                'type' => LocationTypeEnum::WAREHOUSE->value,
                'address' => 'Av Guayacanes #25, Bogotá, Colombia',
                'coordinates' => '4.666706407958432, -74.13390510266406',
                'status' => LocationStatusEnum::ACTIVE->value,
            ],
            [
                'code' => 'LOC-MEM',
                'name' => 'Bodegas Memory Corp',
                'type' => LocationTypeEnum::WAREHOUSE->value,
                'address' => 'Cl. 17a, #69f8, Bogotá, Colombia',
                'coordinates' => '4.647926498487253, -74.1239664596658',
                'status' => LocationStatusEnum::ACTIVE->value,
            ],
            [
                'code' => 'LOC-CAS',
                'name' => 'Conjunto Residencial Casteló Apartamentos PH',
                'type' => LocationTypeEnum::SITE->value,
                'address' => 'Cra. 81f #10B - 40, Kennedy, Bogotá, Cundinamarca, Colombia',
                'coordinates' => '4.649306938505256, -74.14362275479824',
                'status' => LocationStatusEnum::ACTIVE->value,
            ],
            [
                'code' => 'LOC-ARB',
                'name' => 'Conjunto Residencial Arboleda del Salitre',
                'type' => LocationTypeEnum::SITE->value,
                'address' => 'Av. La Esmeralda #22-99, Teusaquillo, Bogotá, Cundinamarca',
                'coordinates' => '4.641703079448298, -74.10497322149081',
                'status' => LocationStatusEnum::ACTIVE->value,
            ],
            [
                'code' => 'LOC-SITC',
                'name' => 'Oficina Principal SITC',
                'type' => LocationTypeEnum::OFFICE->value,
                'address' => 'Cl. 25B #69A 98, Fontibón, Bogotá, Cundinamarca, Colombia',
                'coordinates' => '4.663286765802148, -74.10878038970215',
                'status' => LocationStatusEnum::ACTIVE->value,
            ],
        ];

        foreach ($locations as $attrs) {
            Location::updateOrCreate(['code' => $attrs['code']], $attrs);
        }

        $this->command->info('Seeded locations: '.count($locations));
    }
}
