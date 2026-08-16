<?php

namespace Modules\SaleProposal\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SaleProposalTableSeeder extends Seeder
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
        for ($i=1; $i < 15 ; $i++) { 
            $day   = $faker->numberBetween(1,30);
            $amount= $faker->numberBetween(1000,20000); 
            DB::statement("INSERT INTO `sale_proposals` (`id`, `business_id`, `branch_id`, `customer_type`, `customer_id`, `invoice_no`, `discount_amount`, `order_tax_id`, `shipping_details`, `shipping_address`, `shipping_charge`, `shipping_status`, `created_by`, `created_at`, `updated_at`) VALUES
            ($i, 1, 1, 1, NULL, '200323053105cm8', '30.00', 2, '', '', '100.00', 4, 2, '$date', '$date');");
        
            DB::statement("INSERT INTO `sale_proposal_items` ( `sale_proposal_id`, `business_id`, `branch_id`, `vari_loc_det_id`, `sale_quantity`, `unit_price`, `total_unit_price`, `created_at`, `updated_at`) VALUES
            ( $i,1, 1, 2, '1.00', '$amount', '$amount', '$date', '$date'),
            ( $i,1, 1, 18, '1.00', '$amount', '$amount', '$date', '$date');");
            //payments
            $pay = $faker->numberBetween(500,1200);
            DB::statement("INSERT INTO `sale_proposal_payments` ( `sale_proposal_id`, `payment_method`, `bank_holder_name`, `bank_account_no`, `amount`, `description`, `paid_date`, `document_id`, `created_at`, `updated_at`) VALUES
            ($i, 1, NULL, NULL, '$pay', '', '2023-04-01', NULL, '$date', '$date');");
        }
    }
}
