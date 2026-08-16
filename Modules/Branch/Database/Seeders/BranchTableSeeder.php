<?php

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Modules\Currency\Entities\Currency;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard(); 
        DB::unprepared(file_get_contents(database_path('dummydata/branches.sql')));  
    }
}
