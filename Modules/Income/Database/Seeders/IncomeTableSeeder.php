<?php

namespace Modules\Income\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $date =  Carbon::now()->format('Y-m-d H:i:s');
        //income 
        DB::statement("INSERT INTO `incomes` (`id`, `business_id`, `branch_id`, `account_head_id`, `from_account`, `to_account`, `amount`, `note`, `document_id`, `created_by`, `created_at`, `updated_at`) VALUES
        (1, 1, 1, 1, 7, 2, '100.00', 'Payment received from branch', NULL, 2, '$date', '$date'),
        (2, 1, 1, 1, 7, 2, '100.00', 'Payment received from branch', NULL, 2, '$date', '$date');");
         
        //bank transaction
        DB::statement("INSERT INTO `bank_transactions` ( `user_type`, `business_id`, `branch_id`, `income_id`, `expense_id`, `fund_transfer_id`, `pur_pay_id`, `pur_re_pay_id`, `sale_pay_id`, `pos_pay_id`, `ser_sale_pay_id`, `sale_prop_pay_id`, `account_id`, `document_id`, `type`, `amount`, `note`, `created_at`, `updated_at`) VALUES
        (2, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 1, '100.00', 'Payment received from branch', '$date', '$date'),
        ( 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '100.00', 'Payment received from branch', '$date', '$date'),
        (2, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, 1, '100.00', 'Payment received from branch', '$date', '$date'),
        ( 1, 1, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '100.00', 'Payment received from branch', '$date', '$date');");
    }
}
