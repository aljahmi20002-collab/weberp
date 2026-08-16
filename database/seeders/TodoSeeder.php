<?php

namespace Database\Seeders;

use App\Enums\TodoStatus;
use App\Models\Backend\TodoList;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         = $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   =  $faker->unique()->sentence(50);
        $todo->date          = '1/8/2022'; 
        $todo->save();

        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         =  $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   =  $faker->unique()->sentence(50);
        $todo->date          = '1/8/2022'; 
        $todo->save();

        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         =  $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   = 'Todo list 3';
        $todo->date          = '1/8/2022'; 
        $todo->save();


        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         =  $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   =  $faker->unique()->sentence(50);
        $todo->date          = '1/8/2022'; 
        $todo->save();

        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         =  $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   =  $faker->unique()->sentence(50);
        $todo->date          = '1/8/2022'; 
        $todo->save();

        $todo                = new TodoList();
        $todo->business_id   = 1;
        $todo->title         =  $faker->unique()->sentence(5);
        $todo->date          = '22/07/2022';
        $todo->project_id       = 1;
        $todo->description   =  $faker->unique()->sentence(50); 
        $todo->date          = '1/8/2022'; 
        $todo->save();

        DB::statement("INSERT INTO `todo_list_assigns` (`id`, `todo_list_id`, `user_id`, `created_at`, `updated_at`) VALUES
        (1, 1, 3, '2023-04-11 06:04:52', '2023-04-11 06:04:52'),
        (2, 2, 3, '2023-04-11 06:04:52', '2023-04-11 06:04:52'), 
        (3, 3, 3, '2023-04-11 06:04:52', '2023-04-11 06:04:52'),
        (4, 4, 5, '2023-04-11 06:04:52', '2023-04-11 06:04:52'), 
        (5, 5, 5, '2023-04-11 06:04:52', '2023-04-11 06:04:52');");
    }

}
