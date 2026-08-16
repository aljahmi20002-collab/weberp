<?php

namespace Modules\ServiceSale\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ServiceSaleTableSeeder extends Seeder
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
        for ($i=1; $i < 5 ; $i++) { 
            $amount = $faker->numberBetween(1000,10000);      
            DB::statement("
            INSERT INTO `service_sales` (`id`, `business_id`, `branch_id`, `customer_type`, `customer_id`, `customer_phone`, `invoice_no`, `selling_price`, `order_tax_id`, `shipping_details`, `shipping_address`, `shipping_status`, `order_tax_percent`, `total_price`, `order_tax_amount`, `shipping_charge`, `discount_amount`, `total_sell_price`, `created_by`, `created_at`, `updated_at`) VALUES
             ($i, 1, 1, 1, NULL, '01834735229', '1305240951195T$i', '0.00', 1, 'Need to solve my problem', 'Mirpur ,Dhaka , Bangladesh', NULL, '0.00', '63072.00', '0.00', '0.00', '32.00', '63040.00', 2, '$date', '$date');");

            DB::statement("INSERT INTO `service_sale_items` (`service_sale_id`, `business_id`, `branch_id`, `service_id`, `sale_quantity`, `unit_price`, `total_unit_price`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 2, '1.00', '63072.00', '63072.00', '$date', '$date')");

            DB::statement("INSERT INTO `service_sale_payments` (`id`, `service_sale_id`, `payment_method`, `bank_holder_name`, `bank_account_no`, `amount`, `description`, `paid_date`, `document_id`, `created_at`, `updated_at`) VALUES
            ($i, $i, 1, NULL, NULL, '$amount', '', '2023-05-22', NULL, '$date', '$date');");
        }
    }
}
