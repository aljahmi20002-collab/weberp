<?php

namespace Modules\AccountHead\Database\Seeders;

use App\Enums\StatementType;
use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AccountHead\Entities\AccountHead;

class AccountHeadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $incomeHead            = new AccountHead();
        $incomeHead->type      = StatementType::INCOME;
        $incomeHead->name      = 'Payment received from branch';
        $incomeHead->note      = 'Payment received from branch';
        $incomeHead->is_default= Status::ACTIVE;
        $incomeHead->save();
 
        $expenseHead            = new AccountHead();
        $expenseHead->type      = StatementType::EXPENSE;
        $expenseHead->name      = 'Pay to  branch other cost';
        $expenseHead->note      = 'Pay to  branch other cost';
        $incomeHead->status = Status::ACTIVE;
        $expenseHead->save();
    }
}
