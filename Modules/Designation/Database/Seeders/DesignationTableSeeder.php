<?php

namespace Modules\Designation\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Designation\Entities\Designation;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $designations = [
            'Executive Officer (CEO)',
            'Operating Officer (COO)',
            'Financial Officer (CFO)',
            'Technology Officer (CTO)',
            'Legal Officer (CLO)',
            'Marketing Officer (CMO)'
        ];

        foreach ($designations as $key => $value) {
            $designation           = new Designation();
            $designation->name     = $value;
            $designation->position = $key+1;
            $designation->save();
        }
    }
}
