<?php

namespace Modules\ApplyLeave\Database\Seeders;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ApplyLeave\Entities\LeaveRequest;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Date;
use Modules\LeaveAssign\Entities\LeaveAssign;

class ApplyLeaveTableSeeder extends Seeder
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
        $users = User::where(['status'=>Status::ACTIVE,'is_ban'=>BanUser::UNBAN])->get();

        foreach ($users as   $user) {
            if($user->business):
                $business_id = $user->business->id;
            elseif($user->userBusiness):
                $business_id = $user->userBusiness->id;
            else:
                $business_id = null;
            endif;
            if($user->id !=1):
                $leaveAssign     = LeaveAssign::where(['role_id'=>$user->role->id,'status'=>Status::ACTIVE])->whereYear('created_at',Date('Y'))->get();
                foreach ($leaveAssign as  $assign) {
                    $leave_request                  = new LeaveRequest();
                    $leave_request->employee_id     = $user->id;
                    $leave_request->business_id     = $business_id;
                    $leave_request->role_id         = $user->role_id;
                    $leave_request->leave_assign_id = $assign->id;
                    $leave_request->type_id         = $assign->type_id;
                    $leave_request->manager         = $faker->name;
                    $leave_request->leave_from      = Date('Y-m-d');
                    $leave_request->leave_to        = Carbon::parse(Date('Y-m-d'))->addDays(3)->format('Y-m-d');
                    $leave_request->reason          = $faker->realText(20);
                    $leave_request->save();
                }
            endif;
        }
    }
}
