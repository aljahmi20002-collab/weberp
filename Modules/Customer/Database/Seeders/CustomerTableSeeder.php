<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Enums\CustomerType;
use Faker\Factory as Faker;
class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        Model::unguard();
        $businesses = Business::all();
        foreach ($businesses as $business) { 
            for ($i=0; $i < 6 ; $i++) { 
                $customer                   = new Customer();
                $customer->business_id      = $business->id; 
                $customer->name             = $faker->unique()->name;
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
