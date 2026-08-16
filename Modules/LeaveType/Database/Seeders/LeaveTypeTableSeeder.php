<?php

namespace Modules\LeaveType\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LeaveType\Entities\LeaveType;
class LeaveTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        $types = [
            "Maternity leave",
            "Others",
            "Sick Leave",
            "Vacation Leaves"
        ];
        foreach ($types as $key=>$value) {
             $leaveType       = new LeaveType();
             $leaveType->business_id = 1;
             $leaveType->name = $value;
             $leaveType->position = $key+1;
             $leaveType->save();
        }
    }
}
