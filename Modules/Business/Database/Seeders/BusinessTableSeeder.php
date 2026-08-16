<?php

namespace Modules\Business\Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\Currency\Entities\Currency;
use Faker\Factory as Faker;
class BusinessTableSeeder extends Seeder
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
        $business                 = new  Business();
        $business->business_name  = 'Business';
        $business->start_date     = Date('d/m/Y');
        $business->owner_id       = User::where('user_type',UserType::ADMIN)->first()->id;
        $business->currency_id    = 124;
        $business->barcode_type   = 1;
        $business->save();

        $business                 = new  Business();
        $business->business_name  = 'Elite Group';
        $business->start_date     = Date('d/m/Y');
        $business->owner_id       = 5;
        $business->currency_id    = 124;
        $business->barcode_type   = 1;
        $business->save();
    }
}
