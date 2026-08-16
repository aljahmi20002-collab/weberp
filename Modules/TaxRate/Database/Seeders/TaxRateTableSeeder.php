<?php

namespace Modules\TaxRate\Database\Seeders;

use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Business\Entities\Business;
use Modules\TaxRate\Entities\TaxRate;

class TaxRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $rates = [
            [
                'name'    =>'none',
                'tax_rate'  => 0
            ],
            [
                'name'    =>'CGST@10%',
                'tax_rate'  => 10
            ],
            [
                'name'    =>'CGST@9%',
                'tax_rate'  => 9
            ],
            [
                'name'    =>'SGST@8%',
                'tax_rate'  => 8
            ],
            [
                'name'    =>'SGST@9%',
                'tax_rate'  => 9
            ],
            [
                'name'    =>'VAT@10%',
                'tax_rate'  => 10
            ],
        ];  
        
        $businesses = Business::where('status',Status::ACTIVE)->get();
        foreach ($businesses as  $business) {
            foreach ($rates as $rate) {                    
                $taxRate              = new TaxRate();
                $taxRate->business_id = $business->id;
                $taxRate->name        = $rate['name'];
                $taxRate->tax_rate    = $rate['tax_rate'];
                $taxRate->save();
            }
        }

    }
}
