<?php

namespace Modules\LeaveAssign\Database\Seeders;

use App\Enums\Status;
use App\Models\Backend\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LeaveAssign\Entities\LeaveAssign;
use Modules\LeaveType\Entities\LeaveType;
use Faker\Factory as Faker;
class LeaveAssignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $faker       = Faker::create();
        $roles       = Role::where('status',Status::ACTIVE)->whereNot('id',1)->get();
        $leave_types = LeaveType::where(['business_id'=>1,'status'=>Status::ACTIVE])->get();
        foreach ($roles as  $role) {
            foreach ($leave_types as  $leave_type) {
                $leaveAssign          = new LeaveAssign();
                $leaveAssign->business_id = 1;
                $leaveAssign->role_id = $role->id;
                $leaveAssign->type_id = $leave_type->id;
                $leaveAssign->days    = $faker->randomNumber(3);
                $leaveAssign->save();
            }
        }
    }
}
