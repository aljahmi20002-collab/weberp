<?php

namespace Database\Seeders;

use App\Models\Backend\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 3 ; $i++) {
            $project              = new Project();
            $project->business_id = 1;
            $project->branch_id   = 1;
            $project->title       = $faker->unique()->company;
            $project->date        = '1/2/2022';
            $project->description = $faker->unique()->sentence(50);
            $project->save();
        }
    }
}
