<?php

namespace Modules\FundTransfer\Database\Seeders;

use App\Enums\StatementType;
use App\Enums\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\FundTransfer\Entities\FundTransfer;
use Faker\Factory as Faker;
use Modules\Account\Entities\Account;
use Modules\Account\Entities\BankTransaction;

class FundTransferTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard(); 
        $fundTransfer               = new FundTransfer();
        $fundTransfer->business_id  = 1;
        $fundTransfer->from_account = 1;
        $fundTransfer->to_account   = 2; 
        $fundTransfer->amount       = 5000;
        $fundTransfer->description = Faker::create()->sentence(10);
        $fundTransfer->save();

        $fromAccount             = Account::find(1);
        $fromAccount->balance    = ($fromAccount->balance - 5000);
        $fromAccount->save();

        $bankTransaction              = new BankTransaction();
        $bankTransaction->user_type   = UserType::ADMIN;
        $bankTransaction->business_id = 1;   
        $bankTransaction->fund_transfer_id = $fundTransfer->id;
        $bankTransaction->account_id  = $fromAccount->id;
        $bankTransaction->type        = StatementType::EXPENSE;
        $bankTransaction->amount      = $fundTransfer->amount;
        $bankTransaction->note        = 'fund_transfered_successfully';
        $bankTransaction->save();

        $toAccount             = Account::find(2);
        $toAccount->balance    = ($toAccount->balance + 5000);
        $toAccount->save();

        $bankTransaction              = new BankTransaction();
        $bankTransaction->user_type   = UserType::ADMIN;
        $bankTransaction->business_id = 1;   
        $bankTransaction->fund_transfer_id = $fundTransfer->id;
        $bankTransaction->account_id  = $toAccount->id;
        $bankTransaction->type        = StatementType::INCOME;
        $bankTransaction->amount      = $fundTransfer->amount;
        $bankTransaction->note        = 'fund_transfered_received_successfully';
        $bankTransaction->save();

    }
}
