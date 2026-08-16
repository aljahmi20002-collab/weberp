<?php

namespace Modules\Holiday\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Holiday\Entities\Holiday;
use Faker\Factory as Faker;
class HolidayTableSeeder extends Seeder
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
        $holidays =[
                    "Ashura",
                    "Bengali New Year",
                    "Eid ul-Fitr",
                    "Eid-e-Milad un-Nabi",
                    "Independence Day",
                    "National Mourning Day",
                    "Shaheed Day",
                    "Victory Day"
                ];
        foreach ($holidays as  $holiday) {
            $from  = Date('Y').$faker->unique()->date('-m-d');
            $to    = Carbon::parse($from)->addDays(4)->format('Y-m-d');
            Holiday::create([
                'name'   => $holiday,
                'from'   => $from,
                'to'     => $to ,
                'note'   => $faker->unique()->realText(30)
            ]);
        }
    }
}
