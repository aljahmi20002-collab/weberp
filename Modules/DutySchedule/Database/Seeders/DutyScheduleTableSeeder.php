<?php

namespace Modules\DutySchedule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Role;
use Modules\DutySchedule\Entities\DutySchedule;
use Carbon\Carbon;
class DutyScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $roles = role::whereNot('id',1)->get();
        foreach ($roles as   $role) {
            $dutySchedule             = new DutySchedule();
            $dutySchedule->role_id    = $role->id;
            $dutySchedule->start_time = Carbon::parse('10:00:00')->addMinutes()->format('h:i:s');
            $dutySchedule->end_time   = Carbon::parse('10:00:00')->addHours(8)->format('h:i:s');
            $dutySchedule->save();

        }
    }
}
