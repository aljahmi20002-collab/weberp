<?php

namespace Modules\Attendance\Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Attendance\Entities\Attendance;
use Faker\Factory as Faker;
class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Model::unguard();
         $faker   = Faker::create();
         $users = User::whereNot('user_type',UserType::SUPERADMIN)->get();
         foreach ($users as $user) {
            $attendance = new Attendance(); 
            $attendance->employee_id  = $user->id;
            $attendance->business_id  = businessId($user->id);
            $attendance->date         = date('Y-m-d');
            $attendance->check_in     = $faker->unique()->time('H:i');  
            $attendance->save();
         }
    }
}
