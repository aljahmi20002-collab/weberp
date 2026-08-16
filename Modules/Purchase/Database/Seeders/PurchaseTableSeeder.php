<?php

namespace Modules\Purchase\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PurchaseTableSeeder extends Seeder
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
        $date =  Carbon::now()->format('Y-m-d H:i:s');
        for ($i=1; $i <5; $i++) { 
            DB::statement("INSERT INTO `purchases` (`id`, `business_id`, `supplier_id`, `purchase_no`, `purchase_status`, `tax_id`, `p_tax_percent`, `total_price`, `p_tax_amount`, `total_buy_cost`, `created_by`, `created_at`, `updated_at`) VALUES
            ($i, 1, 6, '130524095924Or$i', 0, 1, '0.00', '2500.00', '0.00', '2500.00', 2, '$date', '$date');");

            DB::statement("INSERT INTO `purchase_items` ( `purchase_id`, `business_id`, `branch_id`, `vari_loc_det_id`, `purchase_quantity`, `unit_cost`, `total_unit_cost`, `profit_percent`, `unit_sell_price`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 1, '5.00', '500.00', '2500.00', '50.00', '750.00', '$date', '$date');");

            //payment
            $pay = $faker->numberBetween(500,1200);
            DB::statement("INSERT INTO `purchase_payments` (`purchase_id`, `payment_method`, `bank_holder_name`, `bank_account_no`, `amount`, `description`, `paid_date`, `document_id`, `created_at`, `updated_at`) VALUES
            ( $i, 1, NULL, NULL, '$pay', '', '2023-04-01', NULL, '$date', '$date');");
        }
 
    }
}
