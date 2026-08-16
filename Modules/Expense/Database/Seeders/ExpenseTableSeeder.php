<?php

namespace Modules\Expense\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ExpenseTableSeeder extends Seeder
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
        for ($i=0; $i <10 ; $i++) { 
            $number  =  $faker->numberBetween(1,2);
            if($number == 1):
                $t=7;
            else:
                $t = 8;
            endif;
            $amount = $faker->numberBetween(50,100); 
            DB::statement("INSERT INTO `expenses` (`business_id`, `branch_id`, `account_head_id`, `from_account`, `to_account`, `amount`, `note`, `document_id`, `created_by`, `created_at`, `updated_at`) VALUES
            (1, $number, 2, 2, $t, $amount, 'Pay to  branch other cost', NULL, 2, '$date', '$date');");
        }
    }
}
