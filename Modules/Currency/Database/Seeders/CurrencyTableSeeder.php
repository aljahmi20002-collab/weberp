<?php

namespace Modules\Currency\Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Modules\Currency\Entities\Currency;
use Monarobase\CountryList\CountryListFacade as Countries;
class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Model::unguard();

        Currency::insert(Config::get('currencies'));
    }
}
