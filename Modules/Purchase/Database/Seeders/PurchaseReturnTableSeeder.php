<?php

namespace Modules\Purchase\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PurchaseReturnTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $date =  Carbon::now()->format('Y-m-d H:i:s');
        Model::unguard();
        for ($i=1; $i <50; $i++) {  
            DB::statement("INSERT INTO `purchase_returns` (`id`, `business_id`, `supplier_id`, `return_no`, `tax_id`, `p_tax_percent`, `total_price`, `p_tax_amount`, `total_buy_cost`, `created_by`, `created_at`, `updated_at`) VALUES
            ($i,1, 6, '130524100453lb$i', 1, '0.00', '2500.00', '0.00', '2500.00', 2, '$date', '$date');");

            DB::statement("INSERT INTO `purchase_return_items` ( `purchase_return_id`, `business_id`, `branch_id`, `vari_loc_det_id`, `return_quantity`, `unit_price`, `total_unit_price`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 3, '5.00', '500.00', '2500.00', '$date', '$date');");

            //payments
            $pay = $faker->numberBetween(500,1200);
            DB::statement("INSERT INTO `purchase_return_payments` ( `purchase_return_id`, `payment_method`, `bank_holder_name`, `bank_account_no`, `amount`, `description`, `paid_date`, `document_id`, `created_at`, `updated_at`) VALUES
            ( $i, 1, NULL, NULL, '$pay', '', '2023-04-01', NULL, '$date', '$date');");

        }
    }
}
