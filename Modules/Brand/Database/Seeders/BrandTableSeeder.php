<?php

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Entities\Brand;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::unprepared(file_get_contents(database_path('dummydata/brands.sql'))); 
    }
}
