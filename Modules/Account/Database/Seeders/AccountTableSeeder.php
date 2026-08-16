<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Account;
use Modules\Account\Enums\AccountType;
use Modules\Account\Enums\PaymentGateway;
use Modules\Business\Entities\Business;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $businesses = Business::limit(2)->get();
        // foreach ($businesses as $business) { 
        //    $account                 = new Account(); 
        //    $account->business_id    = $business->id; 
        //    $account->account_type   = AccountType::ADMIN;
        //    $account->payment_gateway= PaymentGateway::BANK;
        //    $account->bank_name      = 'City Bank';
        //    $account->holder_name    =  $business->business_name;
        //    $account->account_no     = '12345678';
        //    $account->branch_name    = 'Dhaka';
        //    $account->balance        = 30000;
        //    $account->save();

        //    $account                 = new Account(); 
        //    $account->business_id    = $business->id; 
        //    $account->account_type   = AccountType::ADMIN;
        //    $account->payment_gateway= PaymentGateway::MOBILE;
        //    $account->holder_name    =  $business->business_name;
        //    $account->mobile         = '012345678910';
        //    $account->number_type    = 'personal';
        //    $account->balance        = 20000;
        //    $account->save();

        //    $account                 = new Account(); 
        //    $account->business_id    = $business->id; 
        //    $account->account_type   = AccountType::ADMIN;
        //    $account->payment_gateway= PaymentGateway::CASH; 
        //    $account->balance        = 50000;
        //    $account->save(); 
        // }

        DB::statement("INSERT INTO `accounts` (`id`, `business_id`, `branch_id`, `account_type`, `payment_gateway`, `bank_name`, `holder_name`, `account_no`, `branch_name`, `mobile`, `number_type`, `balance`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
        (1, 1, NULL, 1, 2, 'City Bank', 'Goldner PLC', '12345678', 'Dhaka', NULL, NULL, '20000.00', 1, 1, '2023-03-22 05:20:15', '2023-06-05 09:41:09'),
        (2, 1, NULL, 1, 3, NULL, 'Goldner PLC', NULL, NULL, '012345678910', 'personal', '30000.00', 0, 1, '2023-03-22 05:20:15', '2023-06-05 09:41:09'),
        (3, 1, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, '50000.00', 0, 1, '2023-03-22 05:20:15', '2023-03-22 05:20:15'),
        (4, 2, NULL, 1, 2, 'City Bank', 'Johnston, Boyle and Bode', '12345678', 'Dhaka', NULL, NULL, '30000.00', 1, 1, '2023-03-22 05:20:15', '2023-03-22 05:20:15'),
        (5, 2, NULL, 1, 3, NULL, 'Johnston, Boyle and Bode', NULL, NULL, '012345678910', 'personal', '20000.00', 0, 1, '2023-03-22 05:20:15', '2023-03-22 05:20:15'),
        (6, 2, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, '50000.00', 1, 1, '2023-03-22 05:20:15', '2023-03-22 05:20:15'),
        (7, 1, 1, 2, 3, NULL, 'employee', NULL, NULL, '0182222222', 'personal', '0.00', 1, 1, '2023-03-22 05:23:42', '2023-03-22 05:23:42'),
        (8, 2, 2, 2, 3, NULL, 'Employee2', NULL, NULL, '01866666666666', 'Merchant', '0.00', 1, 1, '2023-03-22 05:28:18', '2023-03-22 05:28:18');");
 
    }
}
