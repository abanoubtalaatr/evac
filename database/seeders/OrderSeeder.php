<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Oil;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = User::whereEmail('testuser@gmail.com')->first()->id;
        $carId = Car::first()->id;
        $serviceId = Service::first()->id;
        $oilId = Oil::first()->id;
        $oilBrandId = \App\Models\OilBrand::first()->id;
        $orders = [
          [
              'user_id' => $userId,
              'car_id' => $carId,
              'service_id' => $serviceId,
              'oil_id' => $oilId,
              'oil_brand_id' => $oilBrandId,
              'number_of_kilo' => 34,
              'longitude' => '',
              'latitude' => '',
              'address' => '',
              'payment_type' => 'online',
              'appointment' => now(),
          ],
            [
                'user_id' => $userId,
                'car_id' => $carId,
                'service_id' => $serviceId,
                'oil_id' => $oilId,
                'oil_brand_id' => $oilBrandId,
                'number_of_kilo' => 34,
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'payment_type' => 'offline',
                'appointment' => now(),
            ],
            [
                'user_id' => $userId,
                'car_id' => $carId,
                'service_id' => $serviceId,
                'oil_id' => $oilId,
                'oil_brand_id' => $oilBrandId,
                'number_of_kilo' => 34,
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'payment_type' => 'online',
                'appointment' => now(),
            ],
            [
                'user_id' => $userId,
                'car_id' => $carId,
                'service_id' => $serviceId,
                'oil_id' => $oilId,
                'oil_brand_id' => $oilBrandId,
                'number_of_kilo' => 34,
                'longitude' => '',
                'latitude' => '',
                'address' => '',
                'payment_type' => 'online',
                'appointment' => now(),
            ],
        ];

        foreach ($orders as $order)
        {
            Order::create($order);
        }
    }
}
