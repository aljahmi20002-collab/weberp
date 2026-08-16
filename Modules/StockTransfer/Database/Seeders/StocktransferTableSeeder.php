<?php

namespace Modules\StockTransfer\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class StocktransferTableSeeder extends Seeder
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
        $date =  Carbon::now()->format('Y-m-d H:i:s');
        for ($i=1; $i <50; $i++) {  
            DB::statement("INSERT INTO `stock_transfers` (`id`, `business_id`, `from_branch`, `to_branch`, `transfer_no`, `shipping_charge`, `total_transfer_amount`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 2, '130524095512ST$i', '100.00', '1100.00', 0, 2, '$date', '$date');");

            DB::statement("INSERT INTO `stock_transfer_items` ( `stock_transfer_id`, `business_id`, `vari_loc_det_id`, `to_vari_loc_det_id`, `quantity`, `unit_price`, `total_unit_price`, `created_at`, `updated_at`) VALUES
            ($i, 1, 3, 98, '10.00', '100.00', '1000.00', '$date', '$date');");
        }
        
    }
}
