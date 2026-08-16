<?php

namespace Modules\Service\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Service;
use Faker\Factory as Faker;
use Modules\Service\Enums\ServiceType;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $faker    = Faker::create();
       

        for ($i=0; $i < 20; $i++) { 
            $service  = new Service();
            $service->business_id  = 1;
            $service->name         = $faker->unique()->company;
            $service->description  = $faker->sentence(15);
            $service->price        = rand(3000,99999);
            $service->position     = $i;
            $service->service_type = ServiceType::SUPERADMIN;
            $service->save();
        }
    }
}
