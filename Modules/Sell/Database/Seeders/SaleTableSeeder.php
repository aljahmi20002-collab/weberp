<?php

namespace Modules\Sell\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class SaleTableSeeder extends Seeder
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
        for ($i=1; $i < 30 ; $i++) {  
            DB::statement("INSERT INTO `sales` (`id`, `business_id`, `branch_id`, `customer_type`, `customer_id`, `customer_phone`, `invoice_no`, `shipping_details`, `shipping_address`, `shipping_status`, `order_tax_id`, `order_tax_percent`, `total_price`, `order_tax_amount`, `shipping_charge`, `discount_amount`, `total_sell_price`, `created_by`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 1, NULL, '01820064106', '1305240925495K$i', 'need to home delivery', 'mirpur , dhaka', 1, 1, '0.00', '5000.00', '0.00', '1000.00', '1000.00', '5000.00', 2, '$date', '$date');");
        
            DB::statement("INSERT INTO `sale_items` ( `sale_id`, `business_id`, `branch_id`, `vari_loc_det_id`, `sale_quantity`, `unit_price`, `total_unit_price`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 2, '10.00', '500.00', '5000.00', '$date', '$date');");
            //payments
            $pay = $faker->numberBetween(3000,4000);
            DB::statement("INSERT INTO `sale_payments` ( `sale_id`, `payment_method`, `bank_holder_name`, `bank_account_no`, `amount`, `description`, `paid_date`, `document_id`, `created_at`, `updated_at`) VALUES
            ($i, 1, NULL, NULL, '$pay', '', '2023-04-01', NULL, '$date', '$date');");
        }
    }
}
