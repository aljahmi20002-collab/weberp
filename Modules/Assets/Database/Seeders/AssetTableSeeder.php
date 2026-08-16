<?php

namespace Modules\Assets\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Modules\Assets\Entities\Asset;

class AssetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        // for ($i=0; $i <20; $i++) { 
            
        //     $faker  = Faker::create();
        //     $asset                     = new Asset();
        //     $asset->business_id        = 1;
        //     $asset->branch_id          = $faker->numberBetween(1,2);
        //     $asset->asset_category_id  = $faker->numberBetween(1,5);
        //     $asset->name               = $faker->name;
        //     $asset->supplier           = $faker->name;
        //     $asset->quantity           = $faker->numberBetween(10,50);
        //     $asset->warranty           = $faker->numberBetween(1,5);
        //     $asset->invoice_no         = rand(100000,999999);
        //     $asset->amount             = $faker->numberBetween(1000,99000);
        //     $asset->description        = $faker->realText(200);
        //     $asset->created_by         = 2;
        //     $asset->save();
        // }

    }
}
