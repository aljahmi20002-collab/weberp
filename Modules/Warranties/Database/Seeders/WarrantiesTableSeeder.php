<?php

namespace Modules\Warranties\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Warranties\Entities\Warranty;
use Faker\Factory  as Faker;
use Modules\Warranties\Enums\WarrantyType;

class WarrantiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $faker = Faker::create();
        for ($i=0; $i < 5 ; $i++) { 
            $warranty                  = new Warranty();
            $warranty->business_id     = 1;
            $warranty->name            = $faker->unique()->realText(20);
            $warranty->description     = $faker->unique()->realText(30);
            $warranty->duration        = $faker->unique()->numberBetween(0,20);
            $warranty->duration_type   = WarrantyType::DAY;
            $warranty->position        = $i;
            $warranty->save();
        }
    }
}
