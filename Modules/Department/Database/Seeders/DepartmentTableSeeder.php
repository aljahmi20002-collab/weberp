<?php

namespace Modules\Department\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Department\Entities\Department;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $departments = [
            'General Management',
            'Marketing',
            'Operations',
            'Finance',
            'Sales',
            'Human Resource',
            'Purchase'
        ];
        foreach ($departments as $key => $value) {
            $deparment           = new Department();
            $deparment->name     = $value;
            $deparment->position =  $key+1;
            $deparment->save();
        }
    }
}
