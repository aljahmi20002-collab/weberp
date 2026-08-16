<?php

namespace Modules\Purchase\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PurchaseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PurchaseTableSeeder::class);
        $this->call(PurchaseReturnTableSeeder::class);
    }
}
