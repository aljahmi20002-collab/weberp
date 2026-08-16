<?php

namespace Modules\DutySchedule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\DutySchedule\Database\Seeders\DutyScheduleTableSeeder;
class DutyScheduleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(DutyScheduleTableSeeder::class);
    }
}
