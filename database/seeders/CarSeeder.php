<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = User::whereEmail('testuser@gmail.com')->first()->id;

        $cars = [
          [
              'user_id' => $userId,
              'car_brand_id' => 3,
              'car_module_id' =>2,
              'fuel_type' => 'Diesel',
              'manufacturing_year' => 2010,
              'plat_number' => '1234',
              'plat_char' => 'aaaa',
              'number_of_engine_valves' => 2,
              'color' => '#fff'
          ],
            [
                'user_id' => $userId,
                'car_brand_id' => 3,
                'car_module_id' =>2,
                'fuel_type' => 'Gasoline',
                'manufacturing_year' => 2010,
                'plat_number' => '1234',
                'plat_char' => 'aaaa',
                'number_of_engine_valves' => 3,
                'color' => '#fefefe'
            ],
        ];
        foreach ($cars as $car){
            Car::query()->create($car);
        }
    }
}
