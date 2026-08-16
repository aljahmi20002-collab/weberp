<?php

namespace Modules\Variation\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Variation\Entities\Variation;

class VariationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::unprepared(file_get_contents(database_path('dummydata/variations.sql'))); 
    }
}
