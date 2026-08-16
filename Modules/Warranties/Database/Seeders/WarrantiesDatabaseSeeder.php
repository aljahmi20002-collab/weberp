<?php

namespace Modules\Warranties\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Warranties\Database\Seeders\WarrantiesTableSeeder;
class WarrantiesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(WarrantiesTableSeeder::class);
    }
}
