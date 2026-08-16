<?php

namespace Modules\Plan\Database\Seeders;

use App\Models\Backend\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Plan\Entities\Plan;
use Faker\Factory  as Faker;
use Modules\Plan\Enums\IsDefault;
use Database\Seeders\RoleSeeder;
class PlanTableSeeder extends Seeder
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

        $freePlan               = new Plan(); 
        $freePlan->name         = 'Free trial';
        $freePlan->description  = $faker->unique()->realText(50);
        $freePlan->user_count   = 3;
        $freePlan->days_count   = 7; 
        $freePlan->position     = 3; 
        $freePlan->modules      = $this->FreePlan();
        $freePlan->is_default   = IsDefault::YES;
        $freePlan->save();
 
        $plan               = new Plan(); 
        $plan->name         = 'Basic';
        $plan->description  = $faker->unique()->realText(100);
        $plan->user_count   = $faker->numberBetween(10,20);
        $plan->days_count   = $faker->numberBetween(30,60);
        $plan->price        = $faker->numberBetween(100,200); 
        $plan->modules      = $this->basicPlan();
        $plan->position     = 2;
        $plan->save();
 
        $plan               = new Plan(); 
        $plan->name         = 'Pro';
        $plan->description  = $faker->unique()->realText(100);
        $plan->user_count   = $faker->numberBetween(10,20);
        $plan->days_count   = $faker->numberBetween(30,60);
        $plan->price        = $faker->numberBetween(100,200); 
        $plan->modules      = $this->proPlan();
        $plan->position     = 2;
        $plan->save();
 
    }


    public function FreePlan(){
        return  [
            // "role",
            "users",
            "todo",
            "project",
            "language",
            "activity_logs",
            "login_activity",
            "branch",
            "leave_type",
            "designation",
            "department",
            "leave_assign",
            "apply_leave",
            "available_leave",
            "leave_request",
            "weekend",  
            'sale',
            'sale_proposal',
            'invoice',
            'pos',
        ]; 
    }

    public function basicPlan(){
        return [
            // "role",
            "users",
            "todo", 
            "activity_logs",
            "login_activity",
            "branch",
            "leave_type",
            "designation",
            "department", 
            "apply_leave",
            "available_leave",
            "leave_request",
            "weekend", 
            "attendance",
            "reports",
            "tax_rate",
            "settings",
            "customer",
            'sale',
            'sale_proposal',
            'invoice',
            'pos',
        ]; 
    }
    // public function proPlan(){
    //     return [
    //         "users",
    //         "todo",
    //         "project",
    //         "language",
    //         "activity_logs",
    //         "login_activity",
    //         "branch",
    //         "leave_type",
    //         "designation",
    //         "department",
    //         "leave_assign",
    //         "apply_leave",
    //         "available_leave",
    //         "leave_request",
    //         "weekend",
    //         "holiday",
    //         "duty_schedule",
    //         "attendance",
    //         "reports",
    //         "tax_rate",
    //         "settings",
    //         "customer",
    //         'sale',
    //         'pos',
    //         'invoice',
    //         'stock_transfer'
            
    //     ]; 
    public function proPlan(){
        return [
            'users',
            'todo',
            'project',
            'branch',
            'leave_type',
            'designation',
            'department',
            'leave_assign',
            'apply_leave',
            'available_leave',
            'leave_request',
            'weekend',
            'holiday',
            'duty_schedule',
            'attendance',
            'reports',
            'tax_rate',
            'customer',
            'suppliers',
            'services',
            'products',
            'purchase',
            'purchase_return',
            'sale',
            'sale_proposal',
            'invoice',
            'pos',
            'stock_transfer',
            'account_head',
            'account',
            'fund_transfer',
            'fund_transfer',
            'income',
            'expense',
            'categories',
            'brands',
            'warranties',
            'variations',
            'units',
            'bulk_import'
            
        ]; 
 

    }


    
}
