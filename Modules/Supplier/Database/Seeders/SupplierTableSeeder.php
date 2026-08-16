<?php

namespace Modules\Supplier\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\Supplier\Entities\Supplier;
use Faker\Factory as Faker;
class SupplierTableSeeder extends Seeder
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
        Model::unguard();
        $businesses = Business::all();
        foreach ($businesses as $business) { 
            for ($i=0; $i < 6 ; $i++) { 
                $customer                   = new Supplier();
                $customer->business_id      = $business->id; 
                $customer->name             = $faker->unique()->name;
                $customer->company_name     = $faker->unique()->company;
                $customer->phone            = $faker->unique()->phoneNumber;
                $customer->email            = $faker->unique()->email;
                $customer->address          = $faker->unique()->address;
                $customer->opening_balance  = 0;
                $customer->balance          = 0;
                $customer->save();
                
            }

        }

    }
}
