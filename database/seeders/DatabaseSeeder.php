<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use App\Models\User;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        User::create([
            "_id"=> "user-224c80ab-d72b-4187-bdba-b94f7ac4250c",
            'name' => "admin1",
        	'email' => "admin1@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "admin"
        ]);

        User::create([
            "_id"=> "user-f908a527-3256-4a4f-b841-8c0d8447c32f",
            'name' => "admin2",
        	'email' => "admin2@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "admin"
        ]);

        User::create([
            "_id"=> "user-74c2815b-909c-4ba0-8189-6f71c80aa704",
            'name' => "doni",
        	'email' => "doni@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "user"
        ]);

        User::create([
            "_id"=> "user-81bcf5ad-da31-4fe2-ac22-969f22c709d6",
            'name' => "joni",
        	'email' => "joni@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "user"
        ]);

        User::create([
            "_id"=> "user-c59820d6-4652-4f5e-831a-9574d2799a5f",
            'name' => "david",
        	'email' => "david@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "user"
        ]);

        User::create([
            "_id"=> "user-8c60f1b4-43c7-4e2b-a2e2-b1dfe20cb26f",
            'name' => "laras",
        	'email' => "laras@mail.com",
        	'password' => bcrypt("secret"),
            'role' => "user"
        ]);
        
        Vehicle::create([
            "_id"=> "veh-903b9e6b-a4fb-45e0-8ef1-557708edf458",
            "name"=> "Honda CBR",
            "year"=> 2008,
            "color"=> "green",
            "price"=> 200000000,
            "stock_qty"=> 70,
            "flagtype"=> "Motor",
            "posted_by"=> "user-f908a527-3256-4a4f-b841-8c0d8447c32f",
            "motorcycle_machine"=> "A100",
            "suspension_type"=> "Coil",
            "transmission_type"=> "Manual",
        ]);

        Vehicle::create([
            "_id"=> "veh-e73f2948-a828-46de-b05d-6a90f4a6724b",
            "name"=> "Honda Vario",
            "year"=> 2012,
            "color"=> "brown",
            "price"=> 12000000,
            "stock_qty"=> 220,
            "flagtype"=> "Motor",
            "posted_by"=> "user-f908a527-3256-4a4f-b841-8c0d8447c32f",
            "motorcycle_machine"=> "S12",
            "suspension_type"=> "Coil",
            "transmission_type"=> "Automatic",
        ]);

        Vehicle::create([
            "_id"=> "veh-1fc25892-5854-4612-9fc9-dd6f0627d500",
            "name"=> "Audi R8",
            "year"=> 2020,
            "color"=> "black",
            "price"=> 8000000000,
            "stock_qty"=> 11,
            "flagtype"=> "Mobil",
            "posted_by"=> "user-f908a527-3256-4a4f-b841-8c0d8447c32f",
            "car_machine"=> "V10",
            "capacity"=> 2,
            "car_type"=> "Sport",
        ]);

        Vehicle::create([
            "_id"=> "veh-28a9392f-a859-49fe-88f0-a630ee51f487",
            "name"=> "Honda CRV",
            "year"=> 2018,
            "color"=> "white",
            "price"=> 500000000,
            "stock_qty"=> 111,
            "flagtype"=> "Mobil",
            "posted_by"=> "user-224c80ab-d72b-4187-bdba-b94f7ac4250c",
            "car_machine"=> "X10",
            "capacity"=> 5,
            "car_type"=> "SUV",
        ]);

        VehicleTransaction::create([
            '_id' => "tsc-" . $faker->uuid,
            "qty"=> 1,
            "totalprice"=> 8000000000,
            "id_vehicle"=> "veh-28a9392f-a859-49fe-88f0-a630ee51f487",
            "id_buyer"=> "user-c59820d6-4652-4f5e-831a-9574d2799a5f",
        ]);

        VehicleTransaction::create([
            '_id' => "tsc-" . $faker->uuid,
            "qty"=> 2,
            "totalprice"=> 400000000,
            "id_vehicle"=> "veh-903b9e6b-a4fb-45e0-8ef1-557708edf458",
            "id_buyer"=> "user-81bcf5ad-da31-4fe2-ac22-969f22c709d6",
        ]);

        VehicleTransaction::create([
            '_id' => "tsc-" . $faker->uuid,
            "qty"=> 1,
            "totalprice"=> 500000000,
            "id_vehicle"=> "veh-1fc25892-5854-4612-9fc9-dd6f0627d500",
            "id_buyer"=> "user-74c2815b-909c-4ba0-8189-6f71c80aa704",
        ]);

        VehicleTransaction::create([
            '_id' => "tsc-" . $faker->uuid,
            "qty"=> 1,
            "totalprice"=> 12000000,
            "id_vehicle"=> "veh-e73f2948-a828-46de-b05d-6a90f4a6724b",
            "id_buyer"=> "user-c59820d6-4652-4f5e-831a-9574d2799a5f",
        ]);

        VehicleTransaction::create([
            '_id' => "tsc-" . $faker->uuid,
            "qty"=> 1,
            "totalprice"=> 12000000,
            "id_vehicle"=> "veh-e73f2948-a828-46de-b05d-6a90f4a6724b",
            "id_buyer"=> "user-8c60f1b4-43c7-4e2b-a2e2-b1dfe20cb26f",
        ]);

        

        for ($i = 0; $i < 10; $i++) {
            $vehicle = Vehicle::create([
                '_id' => "veh-" . $faker->uuid,
                'name' => $faker->words(2, true),
                'year' => $faker->numberBetween(1990, 2023),
                'color' => $faker->safeColorName,
                'price' => $faker->numberBetween(10000000, 100000000),
                'stock_qty' => $faker->numberBetween(1, 100),
                'flagtype' => 'Motor',
                'posted_by' => "user-" . $faker->uuid,
                'motorcycle_machine' => $faker->word,
                'suspension_type' => $faker->randomElement(['Coil', 'Air', 'Leaf']),
                'transmission_type' => $faker->randomElement(['Manual', 'Automatic', 'CVT']),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            $vehicle = Vehicle::create([
                '_id' => "veh-" . $faker->uuid,
                'name' => $faker->words(2, true),
                'year' => $faker->numberBetween(1990, 2023),
                'color' => $faker->safeColorName,
                'price' => $faker->numberBetween(100000000, 1000000000),
                'stock_qty' => $faker->numberBetween(1, 100),
                'flagtype' => 'Mobil',
                'posted_by' => "user-" . $faker->uuid,
                'car_machine' => $faker->word,
                'capacity' => $faker->numberBetween(2, 7),
                'car_type' => $faker->randomElement(['Sport', 'SUV', 'City']),
            ]);
        }

    }
}
