<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Faker\Factory as Faker;

class CategoryTableSeeder extends Seeder
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
            $categories = [
                    "Accessories"   => [ 	
                        "Audio", 
                        "Gadgets", 
                        "Computer Accessories",    
                        "Software",
                        "Camera Accessories"	 
                    ],
                    "Books"         => [
                         
                        "Additional",
                        "Translation" ,
                        "olympiad", 
                        "Internet",
                        "Freelancing"
  		 
                    ], 
                    "Electronics"   => ['Jeans'], 
                    "Clothing"         => [
                        "T-Shirt",
                        "Full-Shirt", 
                        'Shoes'
                    ],
                    "Sports"        => [
                        "Football",
                        "Cricket", 
                        "Jersey",  
                    ],
                    "Oil"        => [
                        "Rupchada", 
                    ],
                    "Fruids"        => [
                        "Apple",
                        'banana', 
                        'Pineapple',
                        'Watermelon', 
                    ],
                    "Fruits"      => [
                        "Apple",
                        'banana', 
                        'Pineapple',
                        'Watermelon', 
                        'Milk'
                    ],
                ];
                $i = 0;
                foreach ($categories as $key => $categorys) {
                        $category              = new Category();
                        $category->business_id = 1;
                        $category->name        = $key;
                        $category->description = $faker->unique()->sentence(5,false);
                        $category->position    = ++$i;
                        $category->save();
                        $j=0;
                        foreach ($categorys as $subcategory) {
                            Category::create([
                                'business_id'=> 1,
                                'name'       => $subcategory,
                                'description'=> $faker->unique()->sentence(5,false),
                                'parent_id'  => $category->id,
                                'position'   => ++$j
                            ]);
                        }
                }
               
    }
}
